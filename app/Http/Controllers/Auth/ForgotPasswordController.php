<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\PasswordReset;
use Session;
use Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function forgotPasswordForm()
    {
        return view('frontend.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user)
        {
            return redirect()->back()->withInput()->withErrors(['error' => "We can't find a user with that e-mail address."]);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => \Str::random(60)
            ]
        );

        if ($user && $passwordReset)
        {
            $name = $user->name;
            $email = $user->email;
            $reset_link = url('/reset-password/'.$passwordReset->token);

            $email_template = EmailTemplate::where('type','reset_password')->first();
            
            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}","{{link}}","{{app_name}}");
            $replace = array($name,$reset_link,env('APP_NAME'));
            $content  = str_replace($search,$replace,$content);

            sendEmail($email, $subject, $content, '', '', $lang);

            Session::flash('flash_success', 'We have e-mailed you reset password link! Please check your inbox or spam folder.');
            return redirect()->back();
        }
    }

    public function resetPasswordForm(Request $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        
        if (!$passwordReset)
        {
            Session::flash('flash_danger', 'The password reset token is invalid.');
            return redirect('forgot-password');
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) 
        {
            $passwordReset->delete();

            Session::flash('flash_danger', 'This password reset token is expired.');
            return redirect('admin/forgot-password');
        }

        $data = [];
        $data['email'] = $passwordReset->email;

        return view('frontend.auth.passwords.reset')->with($data);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|max:30|confirmed',
        ]);

        if ($validator->fails())
        {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
            
        $user = User::where('email', $request->email)->first();
        if (!$user)
        {
            Session::flash('flash_danger', "We can't find a user with that e-mail address.");
            return redirect()->back()->withInput();
        }
            
        $user->original_password = $request->password;
        $user->password = Hash::make($request->password);
        $user->save();

        $passwordReset = PasswordReset::where('email', $request->email)->delete();

        Session::flash('flash_success', "Your password has been updated successfully.");
        return redirect('login');
    }
}
