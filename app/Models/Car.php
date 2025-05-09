<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Car extends Model
{
    use HasFactory;


    protected $fillable = [
        'patent',
        'value_rent',
        'id_model',
        'id_brand'
    ];
    
    protected $primaryKey = 'id_car';

    public function car_park()
    {
        return $this->hasMany(Park::class, 'id_car', 'id_car');
    }

    public function car_belongs()
    {
        return $this->hasMany(Belong::class, 'id_car', 'id_car');
    }

    public function car_brand()
    {
        return $this->belongsTo(Brand::class, 'id_brand', 'id_brand');
    }

    public function car_model()
    {
        return $this->belongsTo(ModelCar::class, 'id_model', 'id_model');
    }

    public function car_photo_car()
    {
        return $this->hasMany(PhotoCar::class, 'id_car', 'id_car');
    }

    public function car_need()
    {
        return $this->hasMany(Need::class, 'id_car', 'id_car');
    }

    public function car_utilize()
    {
        return $this->hasMany(Utilize::class, 'id_car', 'id_car');
    }
}
