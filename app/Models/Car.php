<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public function car_park()
    {
        return $this->hasMany(Park::class, 'id_car');
    }

    public function car_belongs()
    {
        return $this->hasMany(Belongs::class, 'id_car');
    }

    public function car_brand()
    {
        return $this->hasMany(Brand::class, 'id_car');
    }

    public function car_model()
    {
        return $this->hasMany(Model_car::class, 'id_car');
    }

    public function car_photo_car()
    {
        return $this->hasMany(Photo_car::class, 'id_car');
    }

    public function car_requires()
    {
        return $this->hasMany(Requires::class, 'id_car');
    }

    public function car_uses()
    {
        return $this->hasMany(Uses::class, 'id_car');
    }
}
