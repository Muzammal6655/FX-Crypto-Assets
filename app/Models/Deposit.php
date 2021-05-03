<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'wallet_address','transaction_id','proof','reason','status'
    ];
    
    public function user()
    {
      return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function transactions()
    {
      return $this->hasMany('App\Models\Transaction', 'deposit_id');
    }
}
