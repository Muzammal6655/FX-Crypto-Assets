<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}