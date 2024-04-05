<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backlog extends Model
{
    use HasFactory;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sprints()
    {
        return $this->hasMany(Sprint::class);
    }

    public function files()
    {
        return $this->hasMany(BacklogFiles::class);
    }
}
