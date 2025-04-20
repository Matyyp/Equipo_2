<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
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
        return $this->hasMany(Parking::class, 'id_service', 'id_service');
    }

    public function service_car_wash()
    {
        return $this->hasMany(CarWash::class, 'id_service', 'id_service');
    }

    public function service_rent()
    {
        return $this->hasMany(Rent::class, 'id_service', 'id_service');
    }
}
