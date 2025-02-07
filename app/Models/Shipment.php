<?php

namespace App\Models;

use App\Models\Scopes\GlobalScope;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shipment extends Model
{
    protected $fillable = ['receiver_id','user_id','weight','isFlex','value','tracking_number','carrier_id','attachment','shipment_price','status','to_address'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function carrier(){
        return $this->belongsTo(Carrier::class);
    }

    protected static function booted()
    {
        static::creating(function ($shipment) {
            $user = Auth::user();
            if ($user->role!=='Admin') {
                $shipment->user_id = Auth::id();
            }
        });
        static::addGlobalScope(new UserScope);
    }

}
