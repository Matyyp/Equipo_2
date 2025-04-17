<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_car extends Model
{
    use HasFactory;

    public function model_car_car()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }
}
