<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'type', 'subject', 'content', 'status'
    ];

    public function emailTemplateLabels()
    {
        return $this->hasMany('App\Models\EmailTemplateLabel', 'email_template_id');
    }
}
