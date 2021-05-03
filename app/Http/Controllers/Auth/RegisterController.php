<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Providers\RouteServiceProvider;
use App\Models\User;
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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'name' => ['required','string','max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country_id' => ['required']
        ]);

        if ($validator->fails())
        {
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'country_id' => $data['country_id'],
            'status' => 2,
            'is_approved' => 0,
            'original_password' => $data['password'],
            'password' => Hash::make($data['password']),
        ]);

        $email = $user->email;

        $email_template = EmailTemplate::where('type','sign_up_confirmation')->first();
        
        $subject = $email_template->subject;
        $content = $email_template->content;

        $hashId = Hashids::encode($user->id);
        $link = url('/verify-account/'.$hashId);
        
        $search = array("{{name}}","{{app_name}}","{{link}}");
        $replace = array($user->username,env('APP_NAME'),$link);
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content);

        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $data['countries'] = Country::all();
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
            ?: redirect($this->redirectPath());
    }

    public function sendRegisterResponse() {
        return redirect($this->redirectPath())
            ->with('flash_success', 'Account verification link has been sent to your account.');
    }
}
