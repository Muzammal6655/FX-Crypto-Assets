<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'wallet_address','reason','status'
    ];

    public function user()
    {
      return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function transactions()
    {
      return $this->hasMany('App\Models\Transaction', 'withdraw_id');
    }
}
