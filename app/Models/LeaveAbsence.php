<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAbsence extends Model
{
    use HasFactory;

    const FAMILY = 'FAMILY';
    const PERSONAL = 'PERSONAL';
    const DISEASE = 'DISEASE';
    const MEDICAL = 'MEDICAL';
    const LEGAL = 'LEGAL';
    const OTHER = 'OTHER';

    const WITH_PAY = 'WITH_PAY';
    const WITHOUT_PAY = 'WITHOUT_PAY';

    public function permits()
    {
        return $this->belongsTo(Permits::class);
    }
}
