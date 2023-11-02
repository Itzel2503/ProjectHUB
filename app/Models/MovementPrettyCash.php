<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementPrettyCash extends Model
{
    use HasFactory;

    public function prettyCash()
    {
        return $this->belongsTo(MovementPrettyCash::class);
    }
}
