<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_daily extends Model
{
    use HasFactory;
    public function contract_daily_contract_parking()
    {
        return $this->belongsTo(Contract_parking::class, 'id_contract');
    }
}
