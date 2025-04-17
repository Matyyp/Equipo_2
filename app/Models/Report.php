<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    public function report_contract()
    {
        return $this->belongsTo(Contract_rent::class, 'id_contract');
    }
    public function report_car_damage()
    {
        return $this->belongsTo(Car_damage::class, 'id_damage');
    }
}
