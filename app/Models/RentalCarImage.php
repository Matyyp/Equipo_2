<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalCarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_car_id',
        'path',
    ];

    public function rentalCar()
    {
        return $this->belongsTo(RentalCar::class);
    }
}
