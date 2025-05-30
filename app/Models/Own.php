<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Own extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_contract',
        'id_accessory'
    ];

    public function owns_accessory()
    {
        return $this->belongsTo(Accessory::class, 'id_accessory', 'id_accessory');
    }

    public function owns_contract_rent()
    {
        return $this->belongsTo(ContractRent::class, 'id_contract', 'id_contract');
    }
}
