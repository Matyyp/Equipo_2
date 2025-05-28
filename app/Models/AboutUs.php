<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'top_text',
        'top_text_active',
        'main_title',
        'main_title_active',
        'secondary_text',
        'secondary_text_active',
        'tertiary_text',
        'tertiary_text_active',
        'button_text',
        'button_active',
        'button_link',
        'button_text_color',
        'button_color',
        'card_color',
        'card_text_color',
        'video_card_color',
        'video_links'
    ];

    protected $casts = [
        'top_text_active' => 'boolean',
        'main_title_active' => 'boolean',
        'secondary_text_active' => 'boolean',
        'tertiary_text_active' => 'boolean',
        'button_active' => 'boolean',
    ];

    public function getVideoLinksArrayAttribute()
    {
        return $this->video_links ? array_filter(explode(',', $this->video_links)) : [];
    }
}