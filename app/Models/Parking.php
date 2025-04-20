<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_service',
        'id_parking',
        'price_net',
        'name_parking',
        'type_parking'
    ];

    public function parking_service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function parking_park()
    {
        return $this->hasMany(Park::class, 'id_service', 'id_service');
    }

    public function parking_parking_register()
    {
        return $this->hasMany(ParkingRegister::class, 'id_service', 'id_service');
    }
}
