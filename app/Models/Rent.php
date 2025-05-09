<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Rent extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_service';

    protected $fillable = [
        'id_service',
    ];
    
    public function rent_enter()
    {
        return $this->hasMany(Enter::class, 'id_service', 'id_service');
    }

    public function rent_service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function rent_allow()
    {
        return $this->hasMany(Allow::class, 'id_service', 'id_service');
    }

    public function rent_utilize()
    {
        return $this->hasMany(Utilize::class, 'id_service', 'id_service');
    }
}
