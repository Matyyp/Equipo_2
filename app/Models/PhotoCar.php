<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_photo_car',
    ];

    public function photo_car_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
}
