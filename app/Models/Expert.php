<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Expert extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table='experts';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image_path',
        'experience',
        'phone_number',
        'address',
        'budget',
        'price',
        'rate'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // connecting with users (for favorites)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type::class);
    }
    public function times()
    {
        return $this->hasMany(Time::class);
    }
}
