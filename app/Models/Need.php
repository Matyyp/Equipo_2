<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    public function require_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
    
    public function require_car_maintenance()
    {
        return $this->belongsTo(CarMaintenance::class, 'id_maintenance', 'id_maintenance');
    }
}
