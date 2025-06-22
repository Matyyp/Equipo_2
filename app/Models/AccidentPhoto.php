<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccidentPhoto extends Model
{
    protected $fillable = ['accident_id', 'photo'];

    public function accident()
    {
        return $this->belongsTo(Accident::class);
    }
}