<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ContractRent extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_contract'
    ];

    public function contract_rent_owns()
    {
        return $this->hasMany(Own::class, 'id_contract', 'id_contract');
    }

    public function contract_rent_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract', 'id_contract');
    }

    public function contract_report()
    {
        return $this->hasMany(Report::class, 'id_contract', 'id_contract');
    }

    public function contract_produces()
    {
        return $this->hasMany(Produce::class, 'id_contract', 'id_contract');
    }
}
