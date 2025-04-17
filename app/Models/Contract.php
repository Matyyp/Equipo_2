<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function contract_presents()
    {
        return $this->hasMany(Presents::class, 'id_contract');
    }

    public function contract_contains()
    {
        return $this->hasMany(Contains::class, 'id_contract');
    }

    public function contract_contract_rent()
    {
        return $this->hasOne(contract_rent::class, 'id_contract');
    }

    public function contract_contract_parking()
    {
        return $this->hasOne(Contract_parking::class, 'id_contract');
    }
}
