<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ParkingRegister extends Model
{
    use HasFactory;


    protected $fillable = [
        'arrival_km',
        'km_exit',
        'total_value',
        'start_date',
        'end_date',
        'days',
        'id_park',
        'status'
    ];

    protected $primaryKey = 'id_parking_register';

    public function parking_register_register()
    {
        return $this->belongsTo(Register::class, 'id_parking_register', 'id_parking_register');
    }

    public function parking_register_generates()
    {
        return $this->hasMany(Generate::class, 'id_parking_register', 'id_parking_register');
    }
    public function park()
    {
        return $this->belongsTo(
            \App\Models\Park::class,
            'id_park',  // FK en parking_registers
            'id'        // PK en parks
        );
    }
}
