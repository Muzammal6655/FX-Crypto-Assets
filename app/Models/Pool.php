<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    protected $fillable = [
     'name','description','min_deposits','max_deposits','users_limit','profit_percentage','management_fee_percentage','days','status'
    ];

    public function poolInvestments()
    {
      return $this->hasMany('App\Models\PoolInvestment', 'pool_id');
    }
}