<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoCar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'url_photo_car',
    ];

    public function photo_car_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
}
