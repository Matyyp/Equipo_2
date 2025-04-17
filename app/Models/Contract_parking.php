<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_parking extends Model
{
    use HasFactory;

    public function contract_parking_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract');
    }

    public function contract_parking_contract_annual()
    {
        return $this->hasOne(Contract_annual::class, 'id_contract');
    }

    public function contract_parking_contract_daily()
    {
        return $this->hasOne(Contract_daily::class, 'id_contract');
    }
}




