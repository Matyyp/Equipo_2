<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $fillable = [
        'register_rent_id', 'user_id', 'external_user_id',
        'stars', 'criterio', 'comentario'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function externalUser() {
        return $this->belongsTo(ExternalUser::class);
    }

    public function rent() {
        return $this->belongsTo(RegisterRent::class, 'register_rent_id');
    }
}
