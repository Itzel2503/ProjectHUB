<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutOfOfficeHours extends Model
{
    use HasFactory;

    const LATE_ARRIVAL = 'Llegada tarde';
    const EARLY_DEPARTURE = 'Salida temprano';
    const HOURS_BETWEEN_SHIFTS = 'Horas entre turno';

    public function userPermission()
    {
        return $this->morphOne(UserPermission::class, 'type');
    }
}
