<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityQuestionAnswer extends Model
{
  protected $fillable = [
   'user_id','question_id','answer','status'
  ];

 
}