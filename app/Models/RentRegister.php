<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_value',
        'return_in',
        'address',
        'driving_licence',
        'class_licence',
        'expire',
        'observation',
        'guarantee',
        'payment',
        'departure_fuel',
        'arrival_fuel',
        'arrival_km',
        'km_exit',
        'start_date',
        'end_date',
    ];

    public function rent_register_produces()
    {
        return $this->hasMany(Produce::class, 'id_rent_register', 'id_rent_register');
    }

    public function rent_register_rent()
    {
        return $this->belongsTo(ContractRent::class, 'id_service', 'id_service');
    }
}
