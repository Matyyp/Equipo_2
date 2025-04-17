<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_service',
        'id_user'
    ];

    public function request_car_wash()
    {
        return $this->belongsTo(CarWash::class, 'id_service',  'id_service');
    }

    public function request_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
