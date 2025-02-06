<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['client_id','amount','method','date','attachment'];

    public function user(){
        $this->belongsTo(User::class);
    }
}
