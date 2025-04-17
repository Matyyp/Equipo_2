<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    public function accessory_owns()
    {
        return $this->hasMany(Owns::class, 'id_accessory');
    }

}
