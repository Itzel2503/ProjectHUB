<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notion extends Model
{
    use HasFactory;

    public function delegate()
    {
        return $this->belongsToMany(User::class, 'notion_users', 'notion_id', 'user_id')->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function chats()
    {
        return $this->hasMany(ChatNotion::class);
    }
}
