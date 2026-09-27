<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionLog extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function userTo(){
    	return $this->belongsTo(Advertiser::class,'to_id');
    }

    public function reffer() {
        return $this->belongsTo(Advertiser::class,'who');
    }

    public function userFrom(){
    	return $this->belongsTo(Advertiser::class,'from_id');
    }
}
