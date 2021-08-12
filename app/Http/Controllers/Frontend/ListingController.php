<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Balance;

class ListingController extends Controller
{
    public function transactions()
    {
        $data['transactions'] = Transaction::where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        return view('frontend.listing.transactions')->with($data);
    }
    public function balances()
    {
        $data['balances'] = Balance::where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        return view('frontend.listing.balances')->with($data);
    }
}
