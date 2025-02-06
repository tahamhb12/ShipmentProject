<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shipment extends Model
{
    protected $fillable = ['sender_id','weight','isFlex','value','tracking_number','carrier_id','attachment','shipment_price','status','to_address'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function carrier(){
        return $this->belongsTo(Carrier::class);
    }

    protected static function booted()
    {
        static::creating(function ($shipment) {
            $shipment->sender_id = Auth::id();
        });
    }

}
