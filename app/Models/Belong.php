<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Belong extends Model
{
    use HasFactory;


    protected $fillable = [
        'id_owner',
        'id_car',
    ];

    public function belongs_owner()
    {
        return $this->belongsTo(Owner::class, 'id_owner', 'id_owner');
    }

    public function belongs_car()
    {
        return $this->belongsTo(Car::class, 'id_car', 'id_car');
    }
}
