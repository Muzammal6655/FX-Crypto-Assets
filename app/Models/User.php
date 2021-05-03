<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'email', 'password', 'original_password', 'status', 'language', 'timezone', 'profile_image', 'last_login','street','city','postcode','country_id','ip_address','otp_auth_status','otp_auth_secret_key','otp_auth_qr_image','is_approved', 'mobile_number', 'family_name', 'dob', 'state', 'invitation_code', 'referral_code', 'referral_code_end_date', 'referrer_account_id', 'btc_wallet_address', 'account_balance', 'deposit_total'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
      parent::boot();
      static::deleting(function($model) 
      {
        /*
        ** Delete user's files
        */

        $path = 'storage/users/'.$model->id;
        if (\File::exists(public_path() . '/' . $path)) 
        {
          \File::deleteDirectory(public_path() . '/' . $path);
        }
        
      });
    }

    // ************************** //
    //        Relationships       //
    // ************************** //

    public function country()
    {
      return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function balances()
    {
      return $this->hasMany('App\Models\Balance', 'user_id');
    }

    public function deposits()
    {
      return $this->hasMany('App\Models\Deposit', 'user_id');
    }

    public function documents()
    {
      return $this->hasMany('App\Models\Document', 'user_id');
    }

    public function passwords()
    {
      return $this->hasMany('App\Models\Password', 'user_id');
    }

    public function poolInvestments()
    {
      return $this->hasMany('App\Models\PoolInvestment', 'user_id');
    }

    public function withdraws()
    {
      return $this->hasMany('App\Models\Withdraw', 'user_id');
    }

    public function transactions()
    {
      return $this->hasMany('App\Models\Transaction', 'user_id');
    }

    public function referrals()
    {
      return $this->hasMany('App\Models\Referral', 'referrer_id');
    }

    public function referralMembers()
    {
      return $this->hasOne('App\Models\Referral', 'refer_member_id');
    }

    public function referrerAccount()
    {
      return $this->belongsTo('App\Models\User','referrer_account_id'); 
    }
    
    // ************************** //
    //  Append Extra Attributes   //
    // ************************** //

    protected $appends = ['profile_image_path','hash_id', 'country_name'];

    public function getProfileImagePathAttribute()
    {
      return $this->attributes['profile_image_path'] = checkImage(asset('storage/users/'.$this->id.'/profile-image/' . $this->profile_image),'avatar.png',$this->profile_image);
    }

    public function getHashIdAttribute()
    {
      return $this->attributes['hash_id'] = \Hashids::encode($this->id);
    }

    public function getCountryNameAttribute()
    {
      return $this->attributes['country_name'] = !empty($this->country_id) ? $this->country->name : '';
    }
}
