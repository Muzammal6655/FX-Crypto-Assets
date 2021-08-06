<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
      'referrer_id', 'refer_member_id'
    ];

    public function referrer()
    {
      return $this->belongsTo('App\Models\User', 'referrer_id');
    }

    public function referMember()
    {
      return $this->belongsTo('App\Models\User', 'refer_member_id');
    }
}
