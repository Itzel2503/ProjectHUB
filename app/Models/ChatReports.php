<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatReports extends Model
{
    use HasFactory;

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function transmitter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
