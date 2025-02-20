<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['client_id','amount','method','date','attachment','details'];

    protected $casts = [
        'attachment' => 'array',
    ];

    public function client(){
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {

        static::addGlobalScope('clientScope', function (Builder $builder) {
            $user = Auth::user();
            if($user->role !=='Admin' && $user->role !=='Manager' && $user->role !=='Accountant'){
                    $builder->where('client_id', Auth::id());
            }
        });
    }
}
