<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Can extends Model
{
    use HasFactory;

    public function can_rent()
    {
        return $this->belongsTo(Rent::class, 'id_service');
    }

    public function can_users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
