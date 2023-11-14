<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function homeOffice()
    {
        return $this->hasMany(HomeOffice::class);
    }

    public function leaveAbsence()
    {
        return $this->hasMany(LeaveAbsence::class);
    }

    public function outOfOfficeHours()
    {
        return $this->hasMany(OutOfOfficeHours::class);
    }

    public function overtime()
    {
        return $this->hasMany(Overtime::class);
    }

    public function vacation()
    {
        return $this->hasMany(Vacation::class);
    }
}
