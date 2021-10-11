<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailTemplate;
use Session;
use Hashids;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('login');
    }

    public function verifyAccount($id)
    {
        $id = Hashids::decode($id)[0];
        $user = User::find($id);

        if(!empty($user->email_verified_at))
        {
            Session::flash('flash_success', 'Your account has already been verified.');
            return redirect()->route('login');
        }
        else if ($user->status == 0 || $user->status == 3) {
            Session::flash('flash_danger', 'Your account has been disabled .Please Contact with admin');
            return redirect()->route('login');
        }

        $user->update(['email_verified_at' => date('Y-m-d H:i:s'), 'status' => 1]);

        // if (Auth::guard('web')->attempt(['email' => $user->email, 'password' => $user->original_password])) {
        //     return redirect()->route('frontend.dashboard.index');
        // }

        Session::flash('flash_success', 'Email address has been verified as there are additional verification steps that are required.');
        return redirect()->route('login');
    }

    public function resendEmail(Request $request)
    {
        $id = Hashids::decode($request->id)[0];
        $user = User::find($id);

        $email_template = EmailTemplate::where('type','sign_up_confirmation')->first();
        
        $email = $user->email;
        $subject = $email_template->subject;
        $content = $email_template->content;

        $link = url('/verify-account/'.$request->id);
        
        $search = array("{{name}}","{{app_name}}","{{link}}");
        $replace = array($user->username,env('APP_NAME'),$link);
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content);

        return redirect()->back()->with('flash_success','Account verification link has been sent to your account.');
    }
}
