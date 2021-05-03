<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
       'user_id','type','amount','actual_amount','description','deposit_id','withdraw_id','fee_amount','fee_percentage'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function deposit()
    {
        return $this->belongsTo('App\Models\Deposit', 'deposit_id');
    }

    public function withdraw()
    {
        return $this->belongsTo('App\Models\Withdraw', 'withdraw_id');
    }
}