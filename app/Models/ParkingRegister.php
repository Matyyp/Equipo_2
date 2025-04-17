<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'arrival_km',
        'km_exit',
        'total_value',
        'start_date',
        'end_date',
        'days',
    ];

    public function parking_register_parking()
    {
        return $this->belongsTo(Parking::class, 'id_service', 'id_service');
    }

    public function parking_register_generates()
    {
        return $this->hasMany(Generate::class, 'id_parking_register', 'id_parking_register');
    }
}
