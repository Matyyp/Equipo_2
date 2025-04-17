<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car_wash extends Model
{
    use HasFactory;

    public function car_wash_service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function car_wash_request()
    {
        return $this->hasMany(Request::class, 'id_service');
    }
}
