<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;

class DashboardController extends Controller
{
    public function index()
    {
        $data['user'] = auth()->user();
        $withdraws = Withdraw::where('user_id',auth()->user()->id)->get();
        $withdrawArr = $months = ["January" => 0, "February" => 0, "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0, "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0];

        foreach ($withdraws as $withdraw) {
            $month = \Carbon\Carbon::createFromTimeStamp(strtotime($withdraw->created_at), "UTC")->tz(session('timezone'))->format('F');

            if(!isset($withdrawArr[$month]))
            {
                $withdrawArr[$month] = 0;   
            }
            $withdrawArr[$month] += $withdraw->actual_amount;
        }
        
        $withdrawYvalues = array();
        foreach ($withdrawArr as $key => $value) {
            $withdrawYvalues[] = $value;
        }

        $data['withdrawYvalues'] = json_encode($withdrawYvalues);

        return view('frontend.dashboard.index', $data);
    }
}
