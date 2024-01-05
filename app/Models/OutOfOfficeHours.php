<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutOfOfficeHours extends Model
{
    use HasFactory;

    const LATE = 'LATE';
    const EARLY = 'EARLY';
    const BETWEEN = 'BETWEEN';
    const FAMILY = 'FAMILY';
    const PERSONAL = 'PERSONAL';
    const DISEASE = 'DISEASE';
    const MEDICAL = 'MEDICAL';
    const LEGAL = 'LEGAL';
    const OTHER = 'OTHER';

    public function permit()
    {
        return $this->morphMany(Permit::class, 'permitable');
    }
}
