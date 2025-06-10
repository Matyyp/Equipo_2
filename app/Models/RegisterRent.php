<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterRent extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'rental_car_id',
        'payment',
        'start_date',
        'end_date',
        'return_in',
        'address',
        'driving_license',
        'class_licence',
        'expire',
        'observation',
        'guarantee',
        'departure_fuel',
        'km_exit',
        'client_rut',     
        'client_name',      
        'client_email',       
    ];


    public function rentalCar()
    {
        return $this->belongsTo(RentalCar::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function userRatings()
    {
        return $this->hasMany(UserRating::class, 'register_rent_id');
    }
}
