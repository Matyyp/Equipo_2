<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generate extends Model
{
    use HasFactory;

    public function generates_parking_register()
    {
        return $this->belongsTo(ParkingRegister::class, 'id_parking_register', 'id_parking_register');
    }

    public function generates_contract_parking()
    {
        return $this->belongsTo(ContractParking::class, 'id_voucher', 'id_voucher');
    }
}
