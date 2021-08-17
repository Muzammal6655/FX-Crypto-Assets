<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Balance;

class ReferralController extends Controller
{
    public function inviteFriend()
    {
        $data['user'] = auth()->user();
        return view('frontend.referral.invite-a-friend')->with($data);
    }
}
