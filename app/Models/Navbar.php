<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    protected $fillable = [
        'reservations', 'reservations_active',
        'schedule', 'schedule_active',
        'email', 'email_active',
        'address', 'address_active',
        'services', 'services_active',
        'about_us', 'about_us_active',
        'contact_us', 'contact_us_active',
        'background_color_1', 'background_color_2',
        'button_1', 'button_color_1', 'button_1_active',
        'button_2', 'button_color_2', 'button_2_active',
        'text_color_1', 'text_color_2',
    ];
}

