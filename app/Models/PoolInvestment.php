<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoolInvestment extends Model
{
    protected $fillable = [
        'user_id','pool_id','deposit_amount','profit_percentage','profit','management_fee_percentage','management_fee','commission','start_date','end_date','reason','status','approved_at'
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