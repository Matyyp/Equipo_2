<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent_register extends Model
{
    use HasFactory;

    public function rent_register_produces()
    {
        return $this->hasMany(Produces::class, 'id_rent_register');
    }

    public function rent_register_rent()
    {
        return $this->belongsTo(Contract_rent::class, 'id_service');
    }
}
