<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Produce extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_contract',
        'id_rent_register'
    ];

    public function produces_contract_rent()
    {
        return $this->belongsTo(ContractRent::class, 'id_contract', 'id_contract');
    }

    public function produces_rent_register()
    {
        return $this->belongsTo(RentRegister::class, 'id_rent_register', 'id_rent_register');
    }
}
