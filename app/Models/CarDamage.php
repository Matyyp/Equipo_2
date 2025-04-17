<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDamage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_damage',
    ];
    
    public function car_damage_report()
    {
        return $this->hasMany(Report::class, 'id_damage', 'id_damage');
    }
}
