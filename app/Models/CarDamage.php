<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarDamage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name_damage',
        'id_contract'
    ];
    
    public function car_damage_report()
    {
        return $this->hasMany(Report::class, 'id_damage', 'id_damage');
    }
}
