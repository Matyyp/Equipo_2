<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarMaintenance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name_maintenance',
        'receipt',
        'next_km',
        'current_km',
        'filtros',
    ];

    public function car_maintenance_requires()
    {
        return $this->hasMany(Need::class, 'id_maintenance', 'id_maintenance');
    }
}
