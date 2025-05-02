<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'id_service',
        'id_parking_register'
    ];

    public function register_parking_register()
    {
        return $this->belongsTo(ParkingRegister::class,'id_parking_register', 'id_parking_register');
    }

    public function register_parking()
    {
        return $this->belongsTo(Parking::class, 'id_service', 'id_service');
    }
    public function paymentRecord()
{
    return $this->hasOne(PaymentRecord::class, 'id_register', 'id');
}
}
