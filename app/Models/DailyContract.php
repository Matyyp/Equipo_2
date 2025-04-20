<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyContract extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id_contract'
    ];
    
    public function contract_daily_contract_parking()
    {
        return $this->belongsTo(ContractParking::class, 'id_contract', 'id_contract');
    }
}
