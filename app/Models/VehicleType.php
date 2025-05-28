<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_title',
        'card_title_active',
        'card_subtitle',
        'card_subtitle_active',
        'text_color',
        'card_background_color',
    ];

    public function image()
    {
        return $this->hasOne(VehicleTypeImage::class);
    }
}
