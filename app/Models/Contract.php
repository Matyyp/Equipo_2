<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function contract_presents()
    {
        return $this->hasMany(Present::class, 'id_contract', 'id_contract');
    }

    public function contract_contains()
    {
        return $this->hasMany(Contain::class, 'id_contract', 'id_contract');
    }

    public function contract_contract_rent()
    {
        return $this->hasOne(ContractRent::class, 'id_contract', 'id_contract');
    }

    public function contract_contract_parking()
    {
        return $this->hasOne(ContractParking::class, 'id_contract', 'id_contract');
    }
}
