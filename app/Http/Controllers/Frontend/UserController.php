<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\Referral;
use App\Models\Password;
use App\Models\kycDocuments;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Auth;
use Hashids;
use File;
use Storage;
use Session;
use Hash;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $data['countries'] = Country::all();
        $data['user'] = $user;
        return view('frontend.users.profile')->with($data);
    }

    public function updateProfile(Request $request)
    { 

        $input = $request->all();
        $user = Auth::user();
        $validations = [
          'dob' => ['required','before:-18 years']
        ];

        $validations['btc_wallet_address'] = [Rule::unique('users')->ignore($user->id)];
        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
       
        
        $change_format   = $input['dob'];
       
        // $change_format =  Carbon::parse($change_format)->format('m-d-Y');
        $change_format = \Carbon\Carbon::CreateFromFormat('d-m-Y', $change_format)->format('d-m-Y');
        $update_Date =  Carbon::parse($user->dob)->format('m-d-Y');
      

        if ($request->has('referral_code') && !empty($request->referral_code)) {
            $referrer = User::where('id', '!=', $user->id)->where('invitation_code', $request->referral_code)->first();

            if (!empty($referrer)) {
                $input['referral_code'] = $request->referral_code;
                $user->referrer_account_id = $referrer->id;
            } else {
                return redirect()->back()->withInput()->withErrors(['error' => 'Referral Code is not valid.']);
            }
        }

        /**
         * Update password Check for previous one
         */
        // dd($user->original_password);
        // if ($request->password  == $user->original_password ) {
        //     return redirect()->back()->withInput()->withErrors(['error' => 'You have already used this password. Please choose a different one.']);
        // }

        /**
         * Update password
         */

        $password = $request->input('password');

        if (!empty($password)) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|max:30|confirmed',
            ]);

            if ($validator->fails()) {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $input['original_password'] = $password;
            $input['password'] = Hash::make($password);

            /**
             * Old password keeping 
             */

            if ($request->password != $user->original_password) {
                
                $password = Password::where(['user_id' => $user->id, 'password' => $request->password])->first();
                if (!empty($password)) {
                    Session::flash('flash_danger', "You have already used this password. Please choose a different one.");
                    return redirect()->back()->withInput();
                }

                Password::Create(
                    [
                        // 'user_id' => $user->id,
                        // 'password' => $user->original_password
                     
                        'user_id' => $user->id,
                        'password' => $request->password
                    ]
                );
            }
        } else {
            unset($input['password']);
        }
        $dob = $request->input('dob');
         
            $flash_message = 'profile has been update';
        if ($request->name != $user->name   || $change_format != $update_Date  ) 
        {       
             $flash_message = 'Your profile has been updated.Please upload the documents again.';
             $user->photo_status = null;
             $user->passport_status = null;
             $user->au_doc_verification_status = 0;
             $user->photo = null;
             $user->passport = null;
             $user->au_doc_verification =null;

        }
        $input['dob'] = \Carbon\Carbon::createFromFormat('d-m-Y', $change_format)->format('Y-m-d');
        $user->update($input);
        if (!empty($referrer)) {
            Referral::create([
                'referrer_id' => $referrer->id,
                'refer_member_id' => $user->id
            ]);
        }

        if (!empty($password)) {
            auth()->logoutOtherDevices($password);
        }

        $request->session()->flash('flash_success', $flash_message);
        return redirect('/profile');
    }

    public function documents()
    {
        $data['user'] = Auth::user();
        return view('frontend.users.documents')->with($data);
    }

    public function uploadDocuments(Request $request)
    {   
         
        $user = Auth::user();
        //10240 
        $validations = [
            'passport' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'photo' => 'required|file|image|mimes:jpg,jpeg,png|max:10240',
            'au_doc_verification' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ];

        $messages = [
            'passport.max' => "Passport size must be less then 10MB.",
            'photo.max' => "Photo size must be less then 10MB.",
            'au_doc_verification.max' => "Au doc size must be less then 10MB.",
        ];
        $validator = Validator::make($request->all(), $validations, $messages);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back();
        }

        if (!empty($request->files) && $request->hasFile('passport')) {
            $file = $request->file('passport');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/' . $user->id . '/documents';
            $filename = 'passport-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            $old_file = public_path() . '/storage/users/' . $user->id . '/documents/' . $user->passport;
            if (file_exists($old_file) && !empty($user->passport)) {
                $res = Storage::delete($target_path . '/' . $user->passport);
            }

            $path = $file->storeAs($target_path, $filename);
            $user->passport = $filename;
            $user->passport_status = 0;
        }

        if (!empty($request->files) && $request->hasFile('photo')) {
            $file = $request->file('photo');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/' . $user->id . '/documents';
            $filename = 'photo-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            $old_file = public_path() . '/storage/users/' . $user->id . '/documents/' . $user->photo;
            if (file_exists($old_file) && !empty($user->photo)) {
                Storage::delete($target_path . '/' . $user->photo);
            }

            $path = $file->storeAs($target_path, $filename);
            $user->photo = $filename;
            $user->photo_status = 0;
        }

        if (!empty($request->files) && $request->hasFile('au_doc_verification')) {
            $file = $request->file('au_doc_verification');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/' . $user->id . '/documents';
            $filename = 'au-doc-file-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            $old_file = public_path() . '/storage/users/' . $user->id . '/documents/' . $user->au_doc_verification;
            if (file_exists($old_file) && !empty($user->au_doc_verification)) {
                Storage::delete($target_path . '/' . $user->au_doc_verification);
            }

            $path = $file->storeAs($target_path, $filename);
            $user->au_doc_verification = $filename;
            $user->au_doc_verification_status = 0;
        }

        $data['document'][1] = $user->passport;
        $data['document'][2] = $user->photo;
        $data['document'][3] = $user->au_doc_verification;
        
        foreach ($data['document'] as $key => $value) {
            $kyc_doc = new kycDocuments();
            $kyc_doc->user_id = $user->id;
            $kyc_doc->document = $data['document'][$key];
            $kyc_doc->doc_type = $key;
            $kyc_doc->status = 1;   
            $kyc_doc->save();    
        }
        
        $email_template = EmailTemplate::where('type', 'document_req')->first();
        
        $setting_days = settingValue('doc_approval_days');
        $name = $user->name;
        $email = $user->email;

        $subject = $email_template->subject;
        $content = $email_template->content;

        $hashId = Hashids::encode($user->id);
       
        $search = array("{{name}}", "{{app_name}}" , "{{setting_days}}");
        $replace = array($user->name,env('APP_NAME'), $setting_days);
        
        $content  = str_replace($search, $replace, $content);

        sendEmail($email, $subject, $content);

 
        $user->save();

        $request->session()->flash('flash_success', 'Documents have been uploaded successfully!');
        return redirect('/documents');
    }

    public function updateEmail(Request $request)
    {

        $user = Auth::user();
        $data['user'] = $user;
        return view('frontend.users.update_email')->with($data);
    }

    public function emailUpdate(Request $request)
    {
         
        $input = $request->all();

        $user = auth()->user();
    
         $validations = [
            'otp_code' => 'required',
            
        ];


        $validator = Validator::make($request->all(), $validations);

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }
        
        if ($user->email_otp_status == 1) {
            if (empty(session()->get('send_otp_email_verification_otp')) || session()->get('send_otp_email_verification_otp') != $input['otp_code']) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Email code is not correct.']);
            }
        }

        $user->email = $input['update_email'];
        $user->save();
        $flash_message = 'Email has been updated successfully.';
        $request->session()->flash('flash_success', $flash_message);
        return redirect('/profile');
    }

    
}
