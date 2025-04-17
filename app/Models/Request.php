<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    public function request_car_wash()
    {
        return $this->belongsTo(Car_wash::class, 'id_service');
    }
    public function request_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
