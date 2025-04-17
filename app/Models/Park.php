<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Park extends Model
{
    use HasFactory;

    public function park_parking()
    {
        return $this->belongsTo(Parking::class, 'id_service');
    }

    public function park_car()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }
}
