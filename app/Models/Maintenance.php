<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_car_id',
        'maintenance_type_id',
        'scheduled_km',
        'scheduled_date',
        'is_completed',
        'employee_name',
        'completed_date',
        'location',
        'invoice_number',
        'invoice_file',
    ];

    public function type()
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }

    public function car()
    {
        return $this->belongsTo(RentalCar::class, 'rental_car_id');
    }

    public function images()
    {
        return $this->hasMany(MaintenanceImage::class);
    }
    public function getRouteKeyName()
    {
        return 'id';
    }
}
