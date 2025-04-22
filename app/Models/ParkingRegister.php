<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingRegister extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'arrival_km',
        'km_exit',
        'total_value',
        'start_date',
        'end_date',
        'days',
    ];

    public function parking_register_register()
    {
        return $this->belongsTo(Register::class, 'id_parking_register', 'id_parking_register');
    }

    public function parking_register_generates()
    {
        return $this->hasMany(Generate::class, 'id_parking_register', 'id_parking_register');
    }
}
