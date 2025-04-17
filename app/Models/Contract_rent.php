<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_rent extends Model
{
    use HasFactory;

    public function contract_rent_owns()
    {
        return $this->hasMany(Owns::class, 'id_contract');
    }

    public function contract_rent_contract()
    {
        return $this->belongsTo(Contract::class, 'id_contract');
    }
}
