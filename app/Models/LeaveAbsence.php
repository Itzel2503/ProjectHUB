<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAbsence extends Model
{
    use HasFactory;

    const WITH_PAY = 'Con goce de sueldo';
    const WITHOUT_PAY = 'Sin goce de sueldo';

    public function permits()
    {
        return $this->belongsTo(Permits::class);
    }
}
