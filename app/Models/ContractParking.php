<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ContractParking extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_contract'
    ];

    public function contract_parking_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract', 'id_contract');
    }

    public function contract_parking_contract_annual()
    {
        return $this->hasOne(AnnualContract::class, 'id_contract', 'id_contract');
    }

    public function contract_parking_contract_daily()
    {
        return $this->hasOne(DailyContract::class, 'id_contract', 'id_contract');
    }
}




