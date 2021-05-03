<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'right_ids', 'status'
    ];

    public function subAdmins()
    {
        return $this->hasMany('App\Models\Admin', 'role_id')->whereNotIn('id', [1, auth()->user()->id]);
    }
}
