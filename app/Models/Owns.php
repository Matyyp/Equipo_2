<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owns extends Model
{
    use HasFactory;
    public function owns_accessory()
    {
        return $this->belongsTo(Accessory::class, 'id_accessory');
    }
    public function owns_contract_rent()
    {
        return $this->belongsTo(Contract_rent::class, 'id_contract');
    }
}
