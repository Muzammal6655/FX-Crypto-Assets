<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id', 'proof_of_id', 'passport','photo','emergency_id_verification_code','au_doc_verification','reason','status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}