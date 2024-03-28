<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;

    public function backlog()
    {
        return $this->belongsTo(Backlog::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
