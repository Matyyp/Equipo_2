<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_accessory',
    ];

    public function accessory_owns()
    {
        return $this->hasMany(Own::class, 'id_accessory', 'id_accessory');
    }

}
