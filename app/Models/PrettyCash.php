<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrettyCash extends Model
{
    use HasFactory;

    public function movementsPrettyCash()
    {
        return $this->hasMany(MovementPrettyCash::class);
    }
}
