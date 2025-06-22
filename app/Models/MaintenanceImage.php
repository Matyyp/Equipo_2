<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenanceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'image_path',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }
}
