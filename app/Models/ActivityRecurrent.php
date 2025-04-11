<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityRecurrent extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_repeat',
        'frequency',
        'day_created',
        'last_date',
        'end_date'
    ];
}
