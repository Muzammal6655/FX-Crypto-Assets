<?php

namespace App\Http\Traits;

trait GraphTrait 
{
    public function graph($records,$column)
    {
        $monthsArr = ["January" => 0, "February" => 0, "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0, "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0];

        /**
         * Deposits history
         */

        foreach ($records as $record) {
            $month = date('F',strtotime($record->created_at));

            if(!isset($monthsArr[$month]))
            {
                $monthsArr[$month] = 0;
            }
            $monthsArr[$month] += $record->$column;
        }
        
        $yValues = array();
        foreach ($monthsArr as $key => $value) {
            $yValues[] = $value;
        }

        return json_encode($yValues);
    }
}