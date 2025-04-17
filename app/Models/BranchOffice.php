<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule',
        'street',
    ];

    public function branch_office_business()
    {
        return $this->belongsTo(Busines::class, 'id_business', 'id_business');
    }

    public function branch_office_location()
    {
        return $this->belongsTo(Location::class, 'id_location', 'id_location');
    }

    public function branch_office_service()
    {
        return $this->hasMany(Service::class, 'id_branch', 'id_branch');
    }

}
