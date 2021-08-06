<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Hashids;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
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

        Session::flash('flash_success', 'Your account has been verified successfully');
        return redirect()->route('login');
    }
}
