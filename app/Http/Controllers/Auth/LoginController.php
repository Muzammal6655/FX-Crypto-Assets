<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    public function __construct()
    {
      $this->middleware('guest:web')->except('logout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
      return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required'
      ]);

      /**
       * Password attempt handling 
       */

      $user = User::where('email',$request->email)->first();
      if(!empty($user))
      {
        if(date('Y-m-d') == $user->password_attempts_date)
        {
          return redirect()->back()->withErrors(['error' => 'Your account is still functioning, but access is restricted until we can sort the issue out. Please contact Interesting FX Admin via email admin@interestingfx.com.']);
        }
        else if($request->password != $user->original_password)
        {
          // Reset attempts
          if($user->password_attempts_count == 3)
          {
            $user->password_attempts_count = 0;
            $user->save();
          }

          $error = '';
          if($user->password_attempts_count == 0)
          {
            $user->password_attempts_count = 1;
            $error = 'Wrong password has been entered once today. This is your first attempt for today.';
          }
          else if($user->password_attempts_count == 1)
          {
            $user->password_attempts_count = 2;
            $error = 'Wrong password has been entered twice today. This is your last attempt for today.';
          }
          else if($user->password_attempts_count == 2)
          {
            $user->password_attempts_count = 3;
            $user->password_attempts_date = date('Y-m-d');
            $error = 'Wrong password has been entered Three times. Your account is still functioning, but access is restricted until we can sort the issue out.';
          }

          $user->save();
          return redirect()->back()->withErrors(['error' => $error]);
        }
      }

      // Attempt to log the user in
      if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        $user = auth()->user();
        $user->password_attempts_count = 0;
        $user->password_attempts_date = Null;
        $user->timezone = $request->timezone;
        $user->save();
        $message = '';
        $is_user_active = true;
        $is_approved = true;

        switch ($user->status) {
          case 0:
            $message = 'Your account has been disabled. Please contact with Admin in case of any concerns.';
            $is_user_active = false;
            break;
          case 2:
            $message = 'Your account is not verified. If you didn`t receive verification email then <a class="font-weight-bold btn-link" href="'.url('/resend-email?id='.\Hashids::encode($user->id)).'">click here.</a>';
            $is_user_active = false;
            break;
          case 3:
            $message = 'Your account has been deleted. Please contact with Admin in case of any concerns.';
            $is_user_active = false;
            break;
        }

        if($is_user_active == false)
        {
          auth()->logout();
          return redirect()->back()->withErrors(['error' => $message]);
        }

        switch ($user->is_approved) {
          case 0:
            $message = 'Your account is under review. Please contact with Admin in case of any concerns.';
            $is_approved = false;
            break;
          case 2:
            $message = 'Your account is rejected. Please contact with Admin in case of any concerns.';
            $is_approved = false;
            break;
        }

        if($is_approved == false)
        {
          auth()->logout();
          return redirect()->back()->withErrors(['error' => $message]);
        }

        if($user->otp_auth_status)
        {
          $data['id'] = \Hashids::encode($user->id);
          $data['email'] = $request->email;
          $data['password'] = $request->password;
          auth()->logout();
          return view('frontend.otp-auth.verify', $data);
        }
        else
        {
          // if successful, then redirect to their intended location
          session(['timezone' => $request->timezone]);
          $cookie_name = 'app_user_id';
          $cookie_id = $user->id;
          setcookie($cookie_name, $cookie_id, 0, "/");
          return redirect()->intended(route('frontend.dashboard'));
        }
      }
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withErrors(['error' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
      Auth::guard('web')->logout();
      setcookie('app_user_id', null, -1, "/"); 
      return redirect()->route('login');
    }
}
