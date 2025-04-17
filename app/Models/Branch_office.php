<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_office extends Model
{
    use HasFactory;

    public function branch_office_business()
    {
        return $this->belongsTo(Business::class, 'id_business');
    }

    public function branch_office_location()
    {
        return $this->belongsTo(Location::class, 'id_location');
    }

    public function branch_office_service()
    {
        return $this->hasMany(Service::class, 'id_service');
    }

}
