<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_contract'
    ];
    
    public function contract_daily_contract_parking()
    {
        return $this->belongsTo(ContractParking::class, 'id_contract', 'id_contract');
    }
}
