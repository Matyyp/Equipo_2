<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Park extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_service',
        'id_car'
    ];

    public function park_parking()
    {
        return $this->belongsTo(Parking::class, 'id_service', 'id_service');
    }

    public function park_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
}
