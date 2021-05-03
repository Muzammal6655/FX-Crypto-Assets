<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoolInvestment extends Model
{
    protected $fillable = [
        'user_id','pool_id','deposit_amount','profit'
    ];
    
    public function pool()
    {
        return $this->belongsTo('App\Models\Pool', 'pool_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}