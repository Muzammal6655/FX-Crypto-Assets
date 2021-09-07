<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Withdraw;
use App\Models\EmailTemplate;
use Hashids;
use Session;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['withdraws'] = Withdraw::where('user_id',auth()->user()->id)->orderBy('created_at','DESC')->paginate(20);
        return view('frontend.withdraws.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { 
        $user = auth()->user();

        // if(date('d') != 15) 
        // {   
        //     return redirect()->back()->withErrors(['error' => 'Withdrawal requests can be received by the 15th of the month.']);
        // }
  
        if(empty($user->btc_wallet_address))
        {   
            return redirect()->back()->withErrors(['error' => 'Wallet address is required for withdraw request.']);
        }
        
        if($user->account_balance <= 0)
        {
            return redirect()->back()->withErrors(['error' => 'You have insufficient balance for the withdrawal request.']);
        }

        $data = array();
        $data['wallet_address'] = $user->btc_wallet_address;
        $data['user'] = $user;

        return view('frontend.withdraws.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        /**
         * OTP Verification
        */

        if ($user->otp_auth_status == 1) {
        if(empty(session()->get('withdraw_request_email_verification_otp')) || session()->get('withdraw_request_email_verification_otp') != $request->email_code)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Email code is not correct.']);
        }
        }

        if($user->otp_auth_status)
        {
            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            // Add the secret key to the user data
            $response = $google2fa->verifyKey($user->otp_auth_secret_key,$request->two_fa_code);

            if(!$response)
            {
               return redirect()->back()->withInput()->withErrors(['error' => '2FA code is not correct.']);
            }
        }

        $model = new Withdraw();
        $model->fill($input);
        $model->user_id = $user->id;
        $model->wallet_address = $user->btc_wallet_address;
        $model->status = 0;
        $model->save();

        $name = $user->name;
        $email = $user->email;
        $link = url('/admin/withdraws/'.Hashids::encode($model->id));

        // ********************* //
        // Send email to Admin   //
        // ********************* //

        $email_template = EmailTemplate::where('type','withdrawal_request')->first();

        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}","{{email}}","{{app_name}}","{{link}}");
        $replace = array($name,$email,env('APP_NAME'),$link);
        $content  = str_replace($search,$replace,$content);

        sendEmail(settingValue('contact_email'), $subject, $content);

        session()->forget('withdraw_request_email_verification_otp');

        $request->session()->flash('flash_success', 'Withdraw has been created successfully. Please wait until admin approves your withdraw.');
        return redirect('/withdraws');
    }

    /**
     * Show the form for creating a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Hashids::decode($id)[0];
        $data['withdraw'] = Withdraw::findOrFail($id);
        return view('frontend.withdraws.view')->with($data);
    }
}
