<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'copyright',
        'contact_text',
        'contact_active',
        'social_text',
        'social_active',
        'background_color',
        'text_color_1',
        'text_color_2',
    ];
}
