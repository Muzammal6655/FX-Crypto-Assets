<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\PasswordReset;
use App\Models\Password;
use App\Models\SecurityQuestion;
use Session;
use Hash;
use Auth;
use Carbon\Carbon;

class ResetPasswordMail extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => \Str::random(60)
            ]
        );

        if ($user && $passwordReset) {
            $name = $user->name;
            $email = $user->email;
            $reset_link = url('/reset-password/' . $passwordReset->token);

            $email_template = EmailTemplate::where('type', 'reset_password')->first();

            $subject = $email_template->subject;
            $content = $email_template->content;

            $search = array("{{name}}", "{{link}}", "{{app_name}}");
            $replace = array($name, $reset_link, env('APP_NAME'));
            $content  = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content);

            Session::flash('flash_success', 'E-mail sent successfully.');
            return redirect('admin/customers');
        }
    }

}
