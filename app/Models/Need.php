<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Need extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_maintenance',
        'id_car'
    ];


    public function require_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
    
    public function require_car_maintenance()
    {
        return $this->belongsTo(CarMaintenance::class, 'id_maintenance', 'id_maintenance');
    }
}
