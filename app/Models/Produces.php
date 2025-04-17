<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produces extends Model
{
    use HasFactory;

    public function produces_contract_rent()
    {
        return $this->belongsTo(Contract_rent::class, 'id_contract');
    }

    public function produces_rent_register()
    {
        return $this->belongsTo(Rent_register::class, 'id_rent_register');
    }
}
