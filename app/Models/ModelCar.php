<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_model',
    ];

    public function model_car_car()
    {
        return $this->hasMany(Car::class, 'id_model', 'id_model');
    }
}
