<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car_damage extends Model
{
    use HasFactory;
    
    public function car_damage_report()
    {
        return $this->hasMany(Report::class, 'id_damage');
    }
}
