<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Heroes extends Model
{
    protected $primaryKey = 'id_hero';

    protected $fillable = [
        'title',
        'title_active',
        'subtitle',
        'subtitle_active',
        'button_text',
        'button_active',
        'button_url',
        'button_color',
        'text_color',
    ];

    public function image()
    {
        return $this->hasOne(HeroImage::class, 'hero_id', 'id_hero');
    }
    
}
