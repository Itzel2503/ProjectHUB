<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatNotion extends Model
{
    use HasFactory;

    public function notion()
    {
        return $this->belongsTo(Notion::class);
    }
}
