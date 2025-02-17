<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notion extends Model
{
    use HasFactory;

    // Agregar los campos que permiten asignación masiva
    protected $fillable = [
        'project_id',    // Aquí añades el campo 'project_id'
        'user_id',
        'note_repeat',
        'color',
        'icon',
        'title',
        'priority',
        'start_date',
        'end_date',
        'status',
        'repeat',
    ];

    public function delegate()
    {
        return $this->belongsToMany(User::class, 'notion_users', 'notion_id', 'delegate_id')->withTimestamps();
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
