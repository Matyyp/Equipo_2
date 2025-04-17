<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    public function evaluation_user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
}
