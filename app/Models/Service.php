<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function service_make()
    {
        return $this->hasMany(Make::class, 'id_service');
    }

    public function service_parking()
    {
        return $this->hasOne(Parking::class, 'id_service');
    }

    public function service_car_wash()
    {
        return $this->hasOne(Car_wash::class, 'id_service');
    }

    public function service_rent()
    {
        return $this->hasOne(Rent::class, 'id_service');
    }
}
