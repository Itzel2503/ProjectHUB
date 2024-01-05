<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeOffice extends Model
{
    use HasFactory;

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
