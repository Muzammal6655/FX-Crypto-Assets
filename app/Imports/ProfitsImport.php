<?php
namespace App\Imports;

use App\Models\PoolInvestment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProfitsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $arr = [];
        foreach ($rows as $key => $row) 
        {
            if($key > 0)
            {
                
            }
        }
    }
}