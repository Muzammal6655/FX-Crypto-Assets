<?php

namespace App\Http\Controllers\Auth\Admin;
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
    protected $redirectTo = '/admin';
    public function __construct()
    {
      $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
      return view('admin.auth.login');
    }

    public function loginAdmin(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required'
      ]);
      
      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

        $user = Auth::guard('admin')->user();
        $message = '';
        $is_active = true;

        switch ($user->status) {
          case 0:
            $message = 'Your account has been disabled. Please contact with Super Admin in case of any concerns.';
            $is_active = false;
            break;
        }

        if($is_active == false)
        {
          Auth::guard('admin')->logout();
          return redirect()->back()->withErrors(['error' => $message]);
        }

        // if successful, then redirect to their intended location
        session(['timezone' => $request->timezone]);
        return redirect()->intended(route('admin.dashboard'));
      }
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withErrors(['error' => 'These credentials do not match our records.']);
    }
    
    public function logout()
    {
      Auth::guard('admin')->logout();
      return redirect()->route('admin.auth.login');
    }
}