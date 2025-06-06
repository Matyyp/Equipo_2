<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Location extends Model
{
    use HasFactory;


    protected $fillable = [
        'commune',
        'id_region'
    ];

    protected $primaryKey = 'id_location';

    public function location_branch_office()
    {
        return $this->hasMany(BranchOffice::class, 'id_location', 'id_location');
    }

    public function location_region()
    {
        return $this->belongsTo(Region::class, 'id_region', 'id');
    }
}
