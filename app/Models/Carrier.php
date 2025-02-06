<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    protected $fillable = ['name','logo','contact_email'];

    public function shipments(){
        return $this->hasMany(Shipment::class);
    }
}
