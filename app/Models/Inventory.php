<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'department_id');
    }

    public function files()
    {
        return $this->hasMany(InventoryFiles::class);
    }
}
