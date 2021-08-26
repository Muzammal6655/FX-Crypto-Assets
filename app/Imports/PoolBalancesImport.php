<?php

namespace App\Imports;

use App\Models\Pool;
use App\Models\PoolBalance;
use Maatwebsite\Excel\Concerns\ToModel;

class PoolBalancesImport implements ToModel
{
    public function model(array $row)
    {
        if($row[0] != 'YYMM')
        {
            $pool = Pool::find($row[1]);
            if(!empty($pool))
            {
                PoolBalance::updateOrCreate(
                    [
                        'pool_id' => $row[1],
                        'year_month' => $row[0],
                    ],
                    [
                        'pool_id' => $row[1],
                        'year_month' => $row[0],
                        'gross_amount' => $row[2],
                        'net_amount' => $row[3],
                    ]
                );
            }  
        }
    }
}