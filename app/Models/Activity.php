<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $dates = ['progress', 'created_at', 'updated_at'];

    // Agregar los campos que permiten asignación masiva
    protected $fillable = [
        'sprint_id',    // Aquí añades el campo 'project_id'
        'user_id',
        'delegate_id',
        'icon',
        'title',
        'content',
        'description',
        'priority',
        'state',
        'points',
        'questions_points',
        'activity_repeat',
        'delegated_date',
        'expected_date',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function getProjectAttribute()
    {
        return $this->sprint && $this->sprint->backlog ? $this->sprint->backlog->project : null;
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatReportsActivities::class);
    }

    public function files()
    {
        return $this->hasMany(ActivityFiles::class);
    }
}
