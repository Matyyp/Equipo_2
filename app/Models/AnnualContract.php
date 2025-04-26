<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnualContract extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_contract',
        'rut',
        'authorized_person',
        'important_note',
        'expiration_date'
    ];

    public function contract_annual_contract_parking()
    {
        return $this->belongsTo(ContractParking::class, 'id_contract', 'id_contract');
    }
}
