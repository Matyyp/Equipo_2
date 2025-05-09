<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Allow extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'id_user',
        'id_service',
    ];

    public function allow_rent()
    {
        return $this->belongsTo(Rent::class, 'id_service', 'id_service');
    }

    public function allow_users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
