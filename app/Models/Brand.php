<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Brand extends Model
{
    use HasFactory;


    protected $fillable = [
        'name_brand',
    ];
    protected $primaryKey = 'id_brand';

    public function brand_car()
    {
        return $this->hasMany(Car::class,'id_brand','id_brand');
    }
}
