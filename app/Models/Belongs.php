<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belongs extends Model
{
    use HasFactory;

    public function belongs_owner()
    {
        return $this->belongsTo(Owner::class, 'id_owner');
    }

    public function belongs_car()
    {
        return $this->belongsTo(Car::class, 'id_car');
    }
}
