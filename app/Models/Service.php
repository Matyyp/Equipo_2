<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_net'
    ];

    public function service_branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'id_branch', 'id_branch');
    }

    public function service_make()
    {
        return $this->hasMany(Make::class, 'id_service', 'id_service');
    }

    public function service_parking()
    {
        return $this->hasOne(Parking::class, 'id_service', 'id_service');
    }

    public function service_car_wash()
    {
        return $this->hasOne(CarWash::class, 'id_service', 'id_service');
    }

    public function service_rent()
    {
        return $this->hasOne(Rent::class, 'id_service', 'id_service');
    }
}
