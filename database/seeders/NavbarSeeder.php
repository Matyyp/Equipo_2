<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Navbar;

class NavbarSeeder extends Seeder
{
    public function run(): void
    {
        Navbar::create([
            'reservations' => 'Reservar',
            'reservations_active' => true,
            'schedule' => 'Lun a Vie: 9:00 - 18:00',
            'schedule_active' => true,
            'email' => 'contacto@ejemplo.cl',
            'email_active' => true,
            'address' => 'Av. Principal 123, Comuna',
            'address_active' => true,
            'services' => 'Servicios',
            'services_active' => true,
            'about_us' => 'Quiénes Somos',
            'about_us_active' => true,
            'contact_us' => 'Contáctanos',
            'contact_us_active' => true,

            'background_color_1' => '#1a1a1a',
            'background_color_2' => '#333333',
            'text_color_1' => '#ffffff',
            'text_color_2' => '#cccccc',

            'button_1' => 'Unete',
            'button_color_1' => '#007bff',
            'button_1_active' => true,
            'button_2' => 'Iniciar Sesión',
            'button_color_2' => '#28a745',
            'button_2_active' => true,
        ]);
    }
}
