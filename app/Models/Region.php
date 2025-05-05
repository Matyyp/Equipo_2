<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_region',
    ];

    public function region_location()
    {
        return $this->hasMany(Location::class, 'id_region', 'id');
    }
}
