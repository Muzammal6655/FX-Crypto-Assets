<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Balance;
use Carbon\Carbon;
use LaravelPDF;
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

    /**
     * Download pdf of Monthly statment.
     *
     * @return \Illuminate\Http\Response
     */
    public function currentMonthStatements(Request $request)
    {
        //Carbon::now()->month
        $data['current_month_statments'] = Transaction::where('user_id',auth()->user()->id)
        ->whereMonth('created_at', Carbon::now()->subMonth(4))->orderBy('id','DESC')->get();

        $data['']=$this->monthStatmentByType('investment');
        return view('pdfs.current_month_statement')->with($data);
        //$pdf = LaravelPDF::loadView('pdfs.monthly_statement', $data);
        //return $pdf->download('current_month_statement '.Carbon::now('UTC')->format('Y-m-d H.i.s').'.pdf');
       
    }


    public function monthStatmentByType($type){
        $data['current_month_statments'] = Transaction::where('user_id',auth()->user()->id)
        ->where('user_id',auth()->user()->id)->whereMonth('created_at', Carbon::now()->subMonth(4))->orderBy('id','DESC')->get();


    }
}
 