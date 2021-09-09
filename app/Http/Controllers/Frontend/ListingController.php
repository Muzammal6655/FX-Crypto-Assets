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
        $data['transactions'] = Transaction::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->paginate(20);
        return view('frontend.listing.transactions')->with($data);
    }

    public function balances()
    {
        $data['user'] = auth()->user();
        $data['balances'] = Balance::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->paginate(20);
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
        $current_month_statments = Transaction::where('user_id', auth()->user()->id)
            ->whereMonth('created_at', Carbon::now()->month)->orderBy('id', 'DESC')->get();
        if (!$current_month_statments->isEmpty()) {
            $data['current_month_statments']  = $current_month_statments;
            $data['total_investment'] = $this->monthStatmentByType('investment');
            $data['total_deposit'] = $this->monthStatmentByType('deposit');
            $data['total_withdraw'] = $this->monthStatmentByType('withdraw');
            $data['total_commission'] = $this->monthStatmentByType('commission');
            $data['total_profit'] = $this->monthStatmentByType('profit');
          
            //return view('pdfs.current_month_statement')->with($data);
   
            $pdf = LaravelPDF::loadView('pdfs.current_month_statement', $data);
            return $pdf->download('current_month_statement ' . Carbon::now('UTC')->format('Y-m-d H.i.s') . '.pdf');
        }
        else{
            return redirect()->back()->withErrors(['error' => "Sorry, you don't have any monthly statement to download."]);
        }
    }


    public function monthStatmentByType($type)
    {
        $result = Transaction::where('user_id', auth()->user()->id)
            ->where('type', $type)->whereMonth('created_at', Carbon::now()->month)->orderBy('id', 'DESC')->sum('actual_amount');

        return $result;
    }
}
