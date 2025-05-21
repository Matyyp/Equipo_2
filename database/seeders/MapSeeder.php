<?php

namespace Database\Seeders;

use App\Models\Map;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    public function run()
    {
        Map::create([
            // Información básica
            'titulo' => 'Nuestra Sede Principal',
            'ciudad' => 'Ciudad Ejemplo',
            'direccion' => 'Calle 123 # 45 - 67',
            'contactos' => '123456789,987654321,contacto@empresa.com',
            'horario' => 'Lunes a Viernes: 8:00 AM - 6:00 PM',
            'texto_boton' => 'Cómo llegar',
            'coordenadas_mapa' => '4.710989,-74.072092',
            'url_boton' => 'https://maps.google.com/?q=4.710989,-74.072092',

            // Activadores de visibilidad
            'map_active' => true,
            'titulo_active' => true,
            'ciudad_active' => true,
            'direccion_active' => true,
            'contactos_active' => true,
            'horario_active' => true,
            'boton_active' => true,

            // Colores grisáceos
            'boton_color' => '#334155',            // slate-700
            'boton_color_texto' => '#f1f5f9',       // gris claro
            'color_tarjeta' => '#1e293b',           // slate-800
            'color_texto_tarjeta' => '#e2e8f0',     // gris claro
            'color_mapa' => '#f8fafc',              // slate-50
        ]);

        $this->command->info('✅ Mapa creado con estilo grisáceo.');
    }
}
