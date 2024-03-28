<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withTimestamps()
                    ->withPivot(['leader', 'programmer']);
    }

    // La relación para obtener solo el líder del proyecto.
    public function leader()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->wherePivot('leader', true);
    }

    // La relación para obtener solo el programador del proyecto.
    public function programmer()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->wherePivot('programmer', true);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function backlog()
    {
        return $this->hasOne(Backlog::class);
    }
}
