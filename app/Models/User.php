<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'type_user', 'date_birthday', 'area_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function permits()
    {
        return $this->belongsToMany(Permit::class)->withPivot(['approved', 'created_at']);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withTimestamps();
    }

    public function clientProjects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->wherePivot('client', true);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
