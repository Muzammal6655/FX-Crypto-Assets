<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Deposit;
use App\Models\Pool;
use App\Models\EmailTemplate;
use Hashids;
use Session;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['deposits'] = Deposit::where('user_id',auth()->user()->id)->orderBy('created_at','DESC')->paginate(10);
        return view('frontend.deposits.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = array();

        if($request->has('pool_id') && !empty($request->pool_id) && $id = Hashids::decode($request->pool_id))
        {
            $pool = Pool::findOrFail($id[0]);
            $data['pool_id'] = $pool->id;
            $data['pool_name'] = $pool->name;
            $data['min_deposits'] = $pool->min_deposits;
            $data['max_deposits'] = $pool->max_deposits;
            $data['wallet_address'] = $pool->wallet_address;
        }
        else
        {
            $data['pool_id'] = '';
            $data['pool_name'] = '';
            $data['min_deposits'] = "0.00000001";
            $data['max_deposits'] = 1000;
            $data['wallet_address'] = settingValue('wallet_address');
        }

        return view('frontend.deposits.create')->with($data);
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
            'wallet_address' => 'required',
            'amount' => 'required',
            'transaction_id' => 'required|unique:deposits',
            'proof' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        /**
         * OTP Verification
         */

        if(empty(session()->get('deposit_email_verification_otp')) || session()->get('deposit_email_verification_otp') != $request->email_code)
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Email code is not correct.']);
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

        $model = new Deposit();

        if (!empty($request->files) && $request->hasFile('proof')) {
            $file = $request->file('proof');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/'.$user->id.'/deposits';
            $filename = 'proof-' . uniqid() .'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs($target_path, $filename);
            $input['proof'] = $filename;
        }

        $model->fill($input);
        $model->user_id = $user->id;
        $model->status = 0;
        $model->save();

        $name = $user->name;
        $email = $user->email;
        $link = url('/admin/deposits/'.Hashids::encode($model->id));

        // ********************* //
        // Send email to Admin   //
        // ********************* //

        $email_template = EmailTemplate::where('type','deposit_request')->first();

        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}","{{email}}","{{app_name}}","{{link}}");
        $replace = array($name,$email,env('APP_NAME'),$link);
        $content  = str_replace($search,$replace,$content);

        sendEmail(settingValue('contact_email'), $subject, $content);

        session()->forget('deposit_email_verification_otp');

        $request->session()->flash('flash_success', 'Deposit has been created successfully. Please wait until admin approves your deposit.');
        return redirect('/deposits');
    }

    /**
     * Show the form for creating a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = Hashids::decode($id)[0];
        $data['deposit'] = Deposit::findOrFail($id);
        return view('frontend.deposits.view')->with($data);
    }
}
