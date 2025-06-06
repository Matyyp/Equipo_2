<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CarWash extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_service';

    protected $fillable = [
        'id_service',
    ];

    public function car_wash_service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function car_wash_request()
    {
        return $this->hasMany(Request::class, 'id_service', 'id_service');
    }
    public function car_wash_parking_register()
    {
        return $this->hasMany(ParkingRegister::class, 'id_service', 'id_service');
    }
}
