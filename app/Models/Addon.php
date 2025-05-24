<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_parking_register',
        'id_service',
    ];

    public function addon_service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function addon_parking_register()
    {
        return $this->belongsTo(ParkingRegister::class, 'id_parking_register', 'id_parking_register');
    }
}
