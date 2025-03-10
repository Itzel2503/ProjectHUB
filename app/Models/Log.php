<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'report_id',
        'activity_id',
        'view',
        'action',
        'message',
        'details',
    ];
}
