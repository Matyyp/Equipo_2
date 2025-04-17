<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;
    
    public function rent_rent_register()
    {
        return $this->hasMany(Rent_register::class, 'id_service');
    }

    public function rent_service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function rent_can()
    {
        return $this->hasMany(Can::class, 'id_service');
    }

    public function rent_uses()
    {
        return $this->hasMany(Uses::class, 'id_service');
    }
}
