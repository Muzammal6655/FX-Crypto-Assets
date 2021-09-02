<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\PoolInvestment;
use App\Http\Traits\GraphTrait;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use LaravelPDF;
use DB;

class DashboardController extends Controller
{
    use GraphTrait;

    public function index()
    {
        $data['user'] = auth()->user();
        $data['total_investments'] = Transaction::where(['user_id' => auth()->user()->id, 'type' => 'investment'])->sum('actual_amount');

        $withdraws = Withdraw::where(['user_id' => auth()->user()->id, 'status' => 1])->get();
        $deposits = Deposit::where(['user_id' => auth()->user()->id, 'status' => 1])->get();
        $investments = PoolInvestment::where(['user_id' => auth()->user()->id, 'status' => 1])->get();

        $data['depositYvalues'] = $this->graph($deposits, 'amount');
        $data['withdrawYvalues'] = $this->graph($withdraws, 'actual_amount');
        $data['investmentsYvalues'] = $this->graph($investments, 'deposit_amount');

        return view('frontend.dashboard.index', $data);
    }


    /**
     * Download pdf of statments.
     *
     * @return \Illuminate\Http\Response
     */
    public function monthlyStatement(Request $request)
    {
        $from =  Carbon::createFromFormat('d/m/Y', '01/' . $request->start_month)->format('Y-m-d');
        $to =  Carbon::createFromFormat('d/m/Y',  $to = '31/' . $request->end_month)->format('Y-m-d');
        $monthly_statment_period = CarbonPeriod::create($from, '1 month', $to);

        $monthly_deposits = Deposit::where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('DATE_FORMAT(created_at,"%Y-%m") month'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('month')->get();


        $monthly_withdraws = Withdraw::where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('DATE_FORMAT(created_at,"%Y-%m") month'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('month')->get();


        $monthly_investments = PoolInvestment::where('user_id', auth()->user()->id)
            ->whereBetween('approved_at', [$from, $to])
            ->select(DB::raw('DATE_FORMAT(approved_at,"%Y-%m") month'), DB::raw('sum(deposit_amount) as total_investment'), DB::raw('sum(profit) as total_profit'))
            ->groupBy('month')->orderby('approved_at')->get();


       
        $monthly_statment=[];
        if (!$monthly_deposits->isEmpty() || !$monthly_withdraws->isEmpty() || !$monthly_investments->isEmpty()) {

            foreach ($monthly_deposits as $deposits) {
                $monthly_statment[$deposits->month]['depositsmonth'] = $deposits->month;

                $monthly_statment[$deposits->month]['total_deposits'] = $deposits->total_amount;
            }

            foreach ($monthly_withdraws as $withdraw) {
                $monthly_statment[$withdraw->month]['total_withdraws'] = $withdraw->total_amount;
            }
            foreach ($monthly_investments as $investment) {
                $monthly_statment[$investment->month]['total_monthly_investments'] = $investment->total_investment;
                $monthly_statment[$investment->month]['total_monthly_investments_profit'] = $investment->total_profit;
            }
        }
        if(count($monthly_statment)){
        
        $data['monthly_statment'] = $monthly_statment;
         $pdf = LaravelPDF::loadView('pdfs.monthly_statement', $data);
        return $pdf->download('monthly_statment ' . Carbon::now('UTC')->format('Y-m-d H.i.s') . '.pdf');
        }
        else{
            return redirect()->back()->withErrors(['error' => "Sorry, you don't have any statement to download."]);
        }
    }
}
