<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatNotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_id',
        'message',
        'look'
    ];

    public function notion()
    {
        return $this->belongsTo(Notion::class);
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
