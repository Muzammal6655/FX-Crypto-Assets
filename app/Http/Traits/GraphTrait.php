<?php

namespace App\Http\Traits;

trait GraphTrait 
{
    public function graph($deposits,$withdraws)
    {
        $depositArr = $withdrawArr = ["January" => 0, "February" => 0, "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0, "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0];

        /**
         * Deposits history
         */

        foreach ($deposits as $deposit) {
            $month = date('F',strtotime($deposit->created_at));

            if(!isset($depositArr[$month]))
            {
                $depositArr[$month] = 0;   
            }
            $depositArr[$month] += $deposit->amount;
        }
        
        $depositYvalues = array();
        foreach ($depositArr as $key => $value) {
            $depositYvalues[] = $value;
        }

        $data['depositYvalues'] = json_encode($depositYvalues);

        /**
         * Withdraws history
         */

        foreach ($withdraws as $withdraw) {
            $month = date('F',strtotime($withdraw->created_at));

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

        return $data;
    }
}