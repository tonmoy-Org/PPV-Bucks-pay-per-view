<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Advertiser extends Authenticatable
{
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'address'           => 'object',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class,'user_id')->where('status','!=',0);
    }



    public function fullname(): Attribute {
        return new Attribute(
            get: fn() => $this->firstname || $this->lastname ? $this->firstname . ' ' . $this->lastname : '@'.$this->username,
        );
    }

     // SCOPES
     public function scopeActive()
     {
         return $this->where('status', 1);
     }

     public function scopeBanned()
     {
         return $this->where('status', 0);
     }

     public function scopeEmailUnverified()
     {
         return $this->where('ev', 0);
     }

     public function scopeMobileUnverified()
     {
         return $this->where('sv', 0);
     }

     public function scopeEmailVerified()
     {
         return $this->where('ev', 1);
     }

     public function scopeMobileVerified()
     {
         return $this->where('sv', 1);
     }

     public function scopeWithBalance()
     {
         return $this->where('balance','>', 0);
     }

}
