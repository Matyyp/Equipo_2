<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accident extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_accident',
        'description',
        'bill_number',
        'description_accident_term',
        'rental_car_id',
        'id_rent',
        'photo',
        'status',
    ];

    public function rentalCar()
    {
        return $this->belongsTo(RentalCar::class, 'rental_car_id');
    }

    public function rent()
    {
        return $this->belongsTo(RegisterRent::class, 'id_rent'); // <-- Relación agregada
    }

    public function photos()
    {
        return $this->hasMany(AccidentPhoto::class);
    }
}