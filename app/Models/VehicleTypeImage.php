<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleTypeImage extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_type_id', 'path'];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
