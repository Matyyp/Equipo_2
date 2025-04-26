<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilize extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_service',
        'id_car'
    ];

    public function utilize_rent()
    {
        return $this->belongsTo(Rent::class, 'id_service','id_service');
    }

    public function utilize_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
}
