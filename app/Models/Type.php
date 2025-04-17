<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table='types';

    protected $fillable = [
        'categories'
    ];

    public function experts()
    {
        return $this->belongsToMany(Expert::class);
    }
}
