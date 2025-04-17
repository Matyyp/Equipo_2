<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uses extends Model
{
    use HasFactory;

    public function uses_rent()
    {
        return $this->belongsTo(Rent::class, 'id_service');
    }
    public function uses_car()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }
}
