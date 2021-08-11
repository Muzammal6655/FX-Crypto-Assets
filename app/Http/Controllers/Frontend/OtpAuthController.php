<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\EmailTemplate;

class OtpAuthController extends Controller
{
    public function info()
    {
        $data['user'] = auth()->user();
        return view('frontend.otp-auth.info')->with($data);
    }

    public function setupTwoFactorAuthentication(Request $request)
    {
        // Get User
        $user = auth()->user();
        $data = array();

        if (!session()->has('incorrect_2fa_otp')) {
            // Initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            // Add the secret key to the user data
            $otp_auth_secret_key = $google2fa->generateSecretKey();

            // Generate the QR image. This is the image the user will scan with their app
            // to set up two factor authentication
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $otp_auth_secret_key
            );

            $user->update([
                'otp_auth_secret_key' => $otp_auth_secret_key,
                'otp_auth_qr_image' => $QR_Image
            ]);

            $data['otp_auth_qr_image'] = $QR_Image;
        }
        else
        {
            $data['otp_auth_qr_image'] = $user->otp_auth_qr_image;
            session()->forget('incorrect_2fa_otp');
        }
        
        return view('frontend.otp-auth.setup', $data);
    }

    public function enableTwoFactorAuthentication(Request $request)
    {
        $messages = [
            'one_time_password.required' => __('The one time password field is required.'),
            'one_time_password.numeric' => __('The one time password must be a number.'),
            'one_time_password.digits' => __('The one time password must be 6 digits.'),
        ];
      
        $validator = Validator::make($request->all(), [
            'one_time_password' => ['required','numeric','digits:6'],
        ]);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        $user = auth()->user();

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the user data
        $response = $google2fa->verifyKey($user->otp_auth_secret_key,$request->one_time_password);

        if($response)
        {
            $user->update([
                'otp_auth_status' => 1
            ]);

            $request->session()->flash('flash_success', __('Two Factor Authentication has been enabled successfully.'));
            return redirect('/otp-auth/info');
        }
        else
        {
            $request->session()->flash('flash_danger', __('Two Factor Authentication (OTP) is not correct.'));
            session()->put('incorrect_2fa_otp', 1);
            return redirect()->back();
        }
    }

    public function disableTwoFactorAuthentication(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'otp_auth_secret_key' => Null,
            'otp_auth_qr_image' => Null,
            'otp_auth_status' => 0
        ]);

        $request->session()->flash('flash_success', __('Two Factor Authentication has been disabled successfully.'));
        return redirect()->back();
    }

    public function verifyTwoFactorAuthentication(Request $request)
    {
        // Get User
        $user = User::where('email',$request->email)->first();

        if(date('Y-m-d') == $user->otp_attempts_date)
        {
            return redirect()->back()->withErrors(['error' => 'Your account is still functioning, but access is restricted until we can sort the issue out.']);
        }

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the user data
        $response = $google2fa->verifyKey($user->otp_auth_secret_key,$request->one_time_password);

        if($response)
        {
            Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password]);
            // if successful, then redirect to their intended location
            session(['timezone' => $request->timezone]);
            return redirect()->intended(route('frontend.dashboard'));
        }
        else
        {   
            $error = '';
            // Reset attempts
            if($user->otp_attempts_count == 2)
            {
                $user->otp_attempts_count = 0;
                $user->save();
            }
            
            if($user->otp_attempts_count == 0)
            { 
                $user->otp_attempts_count = 1;
                $error = 'Wrong Two Factor Authentication (OTP) has been entered once today. This is your first attempt for today.';
            } 
            else if($user->otp_attempts_count == 1)
            {
                $user->otp_attempts_count = 2;
                $user->otp_attempts_date = date('Y-m-d');
                $error = 'You have failed to Log on using 2 factor authentications. 
                          Your account is still functioning, but access is restricted until this 
                          issue is sorted out. Please contact Interesting FX, if someone from 
                          Interesting FX has not contact you via the methods, we have on file within 
                          3 business days.';
            }
        }

        $user->save(); 
        $request->session()->flash('flash_danger', $error);
        return redirect()->back();
    }
 

    public function resetTwoFactorAuthentication(Request $request)
    {
        // Get User
        $user = User::find(\Hashids::decode($request->id)[0]);

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the user data
        $otp_auth_secret_key = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $otp_auth_secret_key
        );

        $user->update([
            'otp_auth_secret_key' => $otp_auth_secret_key,
            'otp_auth_qr_image' => $QR_Image
        ]);

        $name = $user->name;
        $email = $user->email;

        // ********************* //
        // Send email to Support //
        // ********************* //

        $email_template = EmailTemplate::where('type','reset_two_factor_authentication')->first();

        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}","{{email}}","{{app_name}}","{{secret_key}}");
        $replace = array($name,$email,env('APP_NAME'),$otp_auth_secret_key);
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content);

        $request->session()->flash('flash_success', __('Two Factor Authentication reset details has been sent to your email address.'));
        return redirect()->back();
    }
}
