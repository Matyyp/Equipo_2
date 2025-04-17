<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    public function owner_belongs()
    {
        return $this->hasMany(Belongs::class, 'id_owner');
    }
}
