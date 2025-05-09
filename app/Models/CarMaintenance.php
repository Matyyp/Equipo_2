<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CarMaintenance extends Model
{
    use HasFactory;


    protected $fillable = [
        'name_maintenance',
        'receipt',
        'next_km',
        'current_km',
        'filtros',
        'id_car'
    ];

    public function car_maintenance_requires()
    {
        return $this->hasMany(Need::class, 'id_maintenance', 'id_maintenance');
    }
}
