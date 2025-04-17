<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public function location_branch_office()
    {
        return $this->hasMany(Branch_office::class, 'id_location');
    }
}
