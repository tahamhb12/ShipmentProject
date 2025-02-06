<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = ['sender_id','weight','isFlex','value','tracking_number','carrier_id','attachment','shipment_price','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function carrier(){
        return $this->belongsTo(Carrier::class);
    }

}
