<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Park extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_service',
        'id_car',
        'status'
    ];

    public function park_parking()
    {
        return $this->belongsTo(Parking::class, 'id_service', 'id_service');
    }

    public function park_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
    public function service()
    {
        return $this->belongsTo(
            \App\Models\Service::class,
            'id_service', // FK en parks
            'id_service'  // PK en services
        );
    }
}
