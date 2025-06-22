<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
        public function getRouteKeyName()
        {
            return 'id'; // o reemplaza con 'id_maintenance_type' si ese es el nombre real
        }

}
