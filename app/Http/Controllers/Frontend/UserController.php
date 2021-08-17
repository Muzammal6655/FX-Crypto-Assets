<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\Password;
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

        /**
         * Were you referred to Interesting FX?
         */

        if($request->has('referral_code') && !empty($request->referral_code))
        {
            $referrer = User::where('id','!=',$user->id)->where('invitation_code', $request->referral_code)->first();
            if(!empty($referrer))
            {
                $input['referral_code'] = $request->referral_code;    
            }
            else
            {
                return redirect()->back()->withInput()->withErrors(['error' => 'Referral Code is not valid.']);
            }
        }

        /**
         * Update password
         */

        $password = $request->input('password');

        if(!empty($password)){
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|max:30|confirmed',
            ]);

            if ($validator->fails())
            {
                Session::flash('flash_danger', $validator->messages());
                return redirect()->back()->withInput();
            }

            $input['original_password'] = $password;
            $input['password'] = Hash::make($password);

            /**
             * Old password keeping 
             */

            if($request->password != $user->original_password)
            {
                $password = Password::where(['user_id' => $user->id, 'password' => $request->password])->first();
                if(!empty($password))
                {
                    Session::flash('flash_danger', "You have already used this password. Please choose a different one.");
                    return redirect()->back()->withInput();
                }

                Password::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'password' => $user->original_password
                    ],
                    [
                        'user_id' => $user->id,
                        'password' => $user->original_password
                    ]
                );
            }
        }
        else{
            unset($input['password']);
        }
        
        $user->update($input);

        if(!empty($password)){
            auth()->logoutOtherDevices($password);
        }

        $request->session()->flash('flash_success', 'Profile has been updated successfully!');
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

        if (!empty($request->files) && $request->hasFile('passport')) {
            $file = $request->file('passport');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/'.$user->id.'/documents';
            $filename = 'passport-' . uniqid() .'.'.$file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            $old_file = public_path() . '/storage/users/'.$user->id.'/documents/' . $user->passport;
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

            $target_path = 'public/users/'.$user->id.'/documents';
            $filename = 'photo-' . uniqid() .'.'.$file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            $old_file = public_path() . '/storage/users/'.$user->id.'/documents/' . $user->photo;
            if (file_exists($old_file) && !empty($user->photo)) {
                Storage::delete($target_path . '/' . $user->photo);
            }

            $path = $file->storeAs($target_path, $filename);
            $user->photo = $filename;
            $user->photo_status = 0;
        }

        $user->save();

        $request->session()->flash('flash_success', 'Documents have been uploaded successfully!');
        return redirect('/documents');
    }
}