<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\PoolInvestment;
use App\Http\Traits\GraphTrait;

class DashboardController extends Controller
{
    use GraphTrait;

    public function index()
    {
        $data['user'] = auth()->user();
        $deposits = Deposit::where(['user_id' => auth()->user()->id, 'status' => 1])->get();
        $withdraws = Withdraw::where(['user_id' => auth()->user()->id, 'status' => 1])->get();
        $investments = Withdraw::where(['user_id' => auth()->user()->id, 'status' => 1])->get();

        $data['depositYvalues'] = $this->graph($deposits,'amount');
        $data['withdrawYvalues'] = $this->graph($withdraws,'actual_amount');
        $data['investmentsYvalues'] = $this->graph($investments,'deposit_amount');

        return view('frontend.dashboard.index', $data);
    }
}
