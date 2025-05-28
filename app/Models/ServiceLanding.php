<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLanding extends Model
{
    use HasFactory;

    protected $primaryKey = 'service_card_id';
    protected $fillable = [
        'title',
        'title_active',
        'secondary_text',
        'secondary_text_active',
        'small_text',
        'small_text_active',
        'title_color',
        'secondary_text_color',
        'small_text_color',
        'card_background_color',
    ];

    public function image()
    {
        return $this->hasOne(ServiceLandingImage::class, 'service_card_id');
    }
}