<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Deposit;
use App\Models\Pool;
use App\Models\PoolInvestment;
use App\Models\EmailTemplate;
use Hashids;
use Session;
use GuzzleHttp\Client;
use DB;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['deposits'] = Deposit::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(20);
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
        $user = auth()->user();
        $data['model'] = new Pool();
        $data['user'] = $user;
        $data['action'] = "Add";

        if($request->has('pool_id') && !empty($request->pool_id) && $id = Hashids::decode($request->pool_id))
        { 
            // if(date('d') != 01)
            // {
            //     return redirect()->back()->withInput()->withErrors(['error' => 'Pool Investment requests can be received by the 1st of the month.']);
            // }

            $pool = Pool::findOrFail($id[0]);
            $data['pool_id'] = $pool->id;
            $data['pool_name'] = $pool->name;
            $data['min_deposits'] = $pool->min_deposits;
            $data['max_deposits'] = $pool->max_deposits;
            $data['wallet_address'] = $pool->wallet_address;
        } else {
            $data['pool_id'] = '';
            $data['pool_name'] = '';
            $data['min_deposits'] = "0.01";
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
         
        $validations = [
            'wallet_address' => 'required',
            'amount' => 'required',
            'proof' => 'required',
        ];

        if ($input['action'] == 'Add') {
            $validations['transaction_id'] = ['required', Rule::unique('deposits')];
        } else {
            $validations['transaction_id'] = ['required', Rule::unique('deposits')->ignore($input['id'])];
        }

        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        /**
         * OTP Verification
         */
        if ($user->email_otp_status == 1) {
            if (empty(session()->get('deposit_request_email_verification_otp')) || session()->get('deposit_request_email_verification_otp') != $request->email_code) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Email code is not correct.']);
            }
        }

        if ($user->otp_auth_status) {
            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            // Add the secret key to the user data
            $response = $google2fa->verifyKey($user->otp_auth_secret_key, $request->two_fa_code);

            if (!$response) {
                return redirect()->back()->withInput()->withErrors(['error' => '2FA code is not correct.']);
            }
        }
        /**
         * Deposit, Transaction Id OR Hash Verification
        */

        $client = new Client(['base_uri' => 'https://api.etherscan.io']);
        $response = $client->request('GET', '/api?module=transaction&action=gettxreceiptstatus&txhash='.$input['transaction_id'].'&apikey='.env('ETHERSCAN_API_KEY'));
        $response_body = $response->getBody();

        $api_result = json_decode($response_body);
        echo $api_result->result->status;

        if ($api_result->result->status == 0 || $api_result->result->status == "") {
            return redirect()->back()->withInput()->withErrors(['error' => 'Invalid Transaction ID']);
        }

        if ($input['action'] == 'Add') {
            $model = new Deposit();
            $flash_message = 'Deposit has been created successfully.';
        } else {
            $model = Deposit::find($request->id);
            $flash_message = 'Deposit has been update successfully.';
        }

        if (!empty($request->files) && $request->hasFile('proof')) {
            $file = $request->file('proof');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/' . $user->id . '/deposits';
            $filename = 'proof-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            if ($input['action'] == 'Edit') {
                $old_file = public_path() . '/storage/users/' . $user->id . '/deposits';

                if (file_exists($old_file) && !empty($model->proof)) {
                    Storage::delete($target_path . '/' . $model->proof);
                }
            }

            $path = $file->storeAs($target_path, $filename);
            $input['proof'] = $filename;
        }

        $model->fill($input);
        $model->user_id = $user->id;
        $model->status = 0;
        $model->save();

        $name = $user->name;
        $email = $user->email;
        $link = url('/admin/deposits/' . Hashids::encode($model->id));

        // ********************* //
        // Send email to Admin   //
        // ********************* //

        $email_template = EmailTemplate::where('type', 'deposit_request')->first();

        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}", "{{email}}", "{{app_name}}", "{{link}}");
        $replace = array($name, $email, env('APP_NAME'), $link);
        $content  = str_replace($search, $replace, $content);

        sendEmail(settingValue('contact_email'), $subject, $content);

        session()->forget('deposit_request_email_verification_otp');

        $request->session()->flash('flash_success', $flash_message);
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
        $data['pools'] = Pool::where('id','!=',$data['deposit']->pool_id)->get();
        return view('frontend.deposits.view')->with($data);
    }

    public function edit($id, Request $request)
    {
        $id = Hashids::decode($id)[0];
        $user = auth()->user();
        $data['user'] = $user;
        $data['action'] = "Edit";
        $data['model'] = Deposit::findOrFail($id);

        if(!empty($data['model']->pool_id))
        {
            $pool = Pool::findOrFail($data['model']->pool_id);
            $data['pool_id'] = $pool->id;
            $data['pool_name'] = $pool->name;
            $data['min_deposits'] = $pool->min_deposits;
            $data['max_deposits'] = $pool->max_deposits;
            $data['wallet_address'] = $pool->wallet_address;
        } else {
            $data['pool_id'] = '';
            $data['pool_name'] = '';
            $data['min_deposits'] = "0.01";
            $data['max_deposits'] = 1000;
            $data['wallet_address'] = settingValue('wallet_address');
        }

        return view('frontend.deposits.create')->with($data);
    }

    public function transfer(Request $request, $id)
    {      
        $id = Hashids::decode($id)[0];
        $pool = Pool::findOrFail($request->pool_id);
        $model = Deposit::findOrFail($id);
        $pool_investments_count = DB::table('pool_investments')
                          ->where('pool_id', '=' , $pool->id)
                          ->distinct('user_id')
                          ->count();
        
        if($pool_investments_count >=  $pool->users_limit)
        { 
            return redirect()->back()->withInput()->withErrors(['error' => 'User limit of pool is exceeded.']);
        }
 
        if($model->amount >= $pool->min_deposits && $model->amount <= $pool->max_deposits )
        {  
            $model->update([
                'pool_id' => $request->pool_id,
                'status'  => 0,
            ]);
        }
        else
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'Please enter amount greater than or equal to '.$pool->min_deposits.'.']);
        }

        $request->session()->flash('flash_success', 'Deposit amount successfully transfer to '
                                  .$pool->name. '.');
        return redirect()->back();
    }

}
