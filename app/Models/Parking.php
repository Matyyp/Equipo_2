<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Parking extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_service';

    protected $fillable = [
        'id_service',
    ];

    public function parking_service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }

    public function parking_park()
    {
        return $this->hasMany(Park::class, 'id_service', 'id_service');
    }

    public function parking_register()
    {
        return $this->hasMany(Register::class, 'id_service', 'id_service');
    }
}
