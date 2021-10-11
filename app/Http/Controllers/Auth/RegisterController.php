<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Session;
use Hashids;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed'],
            'btc_wallet_address' => ['unique:users', 'nullable'],
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
        }

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->fill($data);

        /**
         * Check Date OF Birthdays
         */

        $newdate = date("m-d-Y", strtotime("-18 year"));
        // dd(date('-18Y'));
        // dd($newdate , $user->dob);
        if ( $newdate > $user->dob ) 
        {
            return redirect()->back()->withInput()->withErrors(['error' => 'You must be atleast 18 years old to setup a account.']);
        }  


        /**
         * Were you referred to Interesting FX?
         */

        $referrer = NULL;

        if ($data['ReferredOptions'] == "yes") {
            if (isset($data['provide_later']) && $data['provide_later'] == "on") {
                $user->referral_code = NULL;
                $user->referral_code_end_date = date("Y-m-t", strtotime("+1 month"));
            } else {
                if (!empty($data['referral_code'])) {
                    $referrer = User::where('invitation_code', $data['referral_code'])->first();
                    if (!empty($referrer)) {
                        $user->referral_code = $data['referral_code'];
                    } else {
                        return redirect()->back()->withInput()->withErrors(['error' => 'Referral Code is not valid.']);
                    }
                } else {
                    return redirect()->back()->withInput()->withErrors(['error' => 'Referral Code is required.']);
                }
            }
        } else {
            $user->referral_code = NULL;
            $user->referral_code_end_date = date("Y-m-t", strtotime("+1 month"));
        }

        /**
         * Do you have an Existing BTC wallet for withdrawals?
         */

        if ($data['BTCOptions'] == "yes") {
            if (!empty($data['btc_wallet_address'])) {
                $user->btc_wallet_address = $data['btc_wallet_address'];
            } else {
                return redirect()->back()->withInput()->withErrors(['error' => 'BTC wallet is required.']);
            }
        } else {
            $user->btc_wallet_address = NULL;
        }
 
 
        $user->dob = \Carbon\Carbon::createFromFormat('m-d-Y', $user->dob)->format('Y-m-d');
        $user->status = 2; // pending
        $user->is_approved = 0; // pending
        $user->original_password = $data['password'];
        $user->password = Hash::make($data['password']);
        $user->ip_address = $_SERVER['REMOTE_ADDR'];
        $user->save();

        if (!empty($referrer)) {
            $user->referrer_account_id = $referrer->id;

            Referral::create([
                'referrer_id' => $referrer->id,
                'refer_member_id' => $user->id
            ]);
        }

        $user->invitation_code = Hashids::encode($user->id);
        $user->save();

        $email_template = EmailTemplate::where('type', 'sign_up_confirmation')->first();

        $email = $user->email;
        $subject = $email_template->subject;
        $content = $email_template->content;

        $hashId = Hashids::encode($user->id);
        $link = url('/verify-account/' . $hashId);

        $search = array("{{name}}", "{{app_name}}", "{{link}}");
        $replace = array($user->name, env('APP_NAME'), $link);
        $content  = str_replace($search, $replace, $content);

        sendEmail($email, $subject, $content);

        //Send Email to admin
        $email_template = EmailTemplate::where('type', 'account_approve_request')->first();

        $email = $user->email;
        $subject = $email_template->subject;
        $content = $email_template->content;

        $hashId = Hashids::encode($user->id);
        $link = url('/admin/investors/' . Hashids::encode($user->id));

        $search = array("{{name}}","{{email}}", "{{app_name}}", "{{link}}");
        $replace = array($user->name,$user->email, env('APP_NAME'), $link);
        $content  = str_replace($search, $replace, $content);

        sendEmail(settingValue('contact_email'), $subject, $content);
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        $data['countries'] = Country::all();
        $data['referral_code'] = $request->has('ref') ? $request->ref : '';
        return view('frontend.auth.register')->with($data);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->sendRegisterResponse();

        return $this->registered($request, $user)
            ?: redirect()->route('login');
    }

    public function sendRegisterResponse()
    {
        return redirect()->route('login')
            ->with('flash_success', 'Thank you for registering, a confimation link has been sent to you email account.');
        // return redirect()
        //     ->with('flash_success', 'Account verification link has been sent to your account.');
    }
}
