<?php

namespace App\Models;

use App\Models\Scopes\GlobalScope;
use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Address extends Model
{
    protected $fillable = ['user_id','street_address','city','state','postal_code','country'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::creating(function ($address) {
            $user = Auth::user();
            if ($user->role!=='Admin') {
                $address->user_id = Auth::id();
            }
        });
        static::addGlobalScope(new UserScope);
    }

}
