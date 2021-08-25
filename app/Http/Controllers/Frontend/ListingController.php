<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\PoolInvestment;
use Hashids;

class ListingController extends Controller
{
    public function transactions()
    {
        $data['transactions'] = Transaction::where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        return view('frontend.listing.transactions')->with($data);
    }
    
    public function balances()
    {
        $data['user'] = auth()->user();
        $data['balances'] = Balance::where('user_id',auth()->user()->id)->orderBy('id','DESC')->paginate(10);
        return view('frontend.listing.balances')->with($data);
    }

    public function transactionDetail($id)
    {
        $id = Hashids::decode($id)[0];
        $data['transaction'] = Transaction::findOrFail($id);
        return view('frontend.listing.transaction_detail')->with($data);
    }
}
