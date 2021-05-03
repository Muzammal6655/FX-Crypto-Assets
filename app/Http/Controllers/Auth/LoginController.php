<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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

      // Attempt to log the user in
      if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        $user = auth()->user();

        $message = '';
        $is_user_active = true;
        $is_approved = true;

        switch ($user->status) {
          case 0:
            $message = 'Your account has been disabled. Please contact with Admin in case of any concerns.';
            $is_user_active = false;
            break;
          case 2:
            $message = 'Your account is not verified. Please contact with Admin in case of any concerns.';
            $is_user_active = false;
            $resend_email_flag = true;
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

        // if successful, then redirect to their intended location
        session(['timezone' => $request->timezone]);
        return redirect()->intended(route('frontend.user.dashboard'));
      }
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withErrors(['error' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
      Auth::guard('web')->logout();
      return redirect()->route('login');
    }
}
