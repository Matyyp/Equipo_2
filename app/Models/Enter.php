<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Enter extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_service',
        'id_rent_register'
    ];

    public function enter_rent()
    {
        return $this->belongsTo(RentRegister::class,'id_rent_register', 'id_rent_register');
    }

    public function enter_rent_register()
    {
        return $this->belongsTo(Rent::class, 'id_service', 'id_service');
    }
}
