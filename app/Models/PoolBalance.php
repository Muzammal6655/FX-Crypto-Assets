<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoolBalance extends Model
{
    protected $fillable = [
        'pool_id', 'year_month', 'gross_amount', 'net_amount'
    ];

    public function pool()
    {
        return $this->belongsTo('App\Models\Pool', 'pool_id');
    }
}
