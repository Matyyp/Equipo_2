<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Report extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_contract',
        'id_damage'
    ];

    public function report_contract()
    {
        return $this->belongsTo(ContractRent::class, 'id_contract','id_contract');
    }

    public function report_car_damage()
    {
        return $this->belongsTo(CarDamage::class, 'id_damage', 'id_damage');
    }
}
