<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Footer;

class FooterSeeder extends Seeder
{
    public function run()
    {
        Footer::create([
            'copyright'        => '© 2025 MiEmpresa. Todos los derechos reservados.',
            'contact_text'     => 'contacto@miempresa.com,+56 9 1234 5678',
            'contact_active'   => true,
            'social_text'      => 'facebook.com/miempresa,instagram.com/miempresa',
            'social_active'    => true,
            'background_color' => '#222222',
            'text_color_1'     => '#ffffff',
            'text_color_2'     => '#cccccc',
        ]);
    }
}
