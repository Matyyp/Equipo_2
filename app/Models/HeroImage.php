<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    protected $fillable = ['hero_id', 'path'];

    public function hero()
    {
        return $this->belongsTo(Heroes::class, 'hero_id', 'id_hero');
    }
}
