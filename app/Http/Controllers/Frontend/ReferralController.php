<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function inviteFriend()
    {
        $data['user'] = auth()->user();
        $data['referrals'] = Referral::where('referrer_id',auth()->user()->id)->paginate(10);
        return view('frontend.referral.invite-a-friend')->with($data);
    }
}
