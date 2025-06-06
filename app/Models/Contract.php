<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchOfficeScope;


class Contract extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_branch_office',
    ];

    protected $primaryKey = 'id_contract';

    protected static function booted()
    {
        static::addGlobalScope(new BranchOfficeScope);
    }

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

    public function contract_branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'id_branch_office', 'id_branch');
    }
}
