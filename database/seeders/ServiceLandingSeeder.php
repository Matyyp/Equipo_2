<?php

namespace Database\Seeders;

use App\Models\ServiceLanding;
use Illuminate\Database\Seeder;

class ServiceLandingSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'title' => 'Servicio 1',
                'title_active' => true,
                'secondary_text' => 'Agrega imágenes desde el panel de administración.',
                'secondary_text_active' => true,
                'small_text' => 'O borra estos slides si no los quieres.',
                'small_text_active' => true,
                'title_color' => '#1e293b',                // slate-800
                'secondary_text_color' => '#64748b',        // slate-500
                'small_text_color' => '#94a3b8',            // slate-400
                'card_background_color' => '#f8fafc',       // slate-50
            ],
            [
                'title' => 'Servicio 2',
                'title_active' => true,
                'secondary_text' => 'Agrega imágenes desde el panel de administración.',
                'secondary_text_active' => true,
                'small_text' => 'O borra estos slides si no los quieres.',
                'small_text_active' => true,
                'title_color' => '#1e293b',                // slate-800
                'secondary_text_color' => '#64748b',        // slate-500
                'small_text_color' => '#94a3b8',            // slate-400
                'card_background_color' => '#f1f5f9',       // slate-100
            ],
        ];

        foreach ($services as $serviceData) {
            $service = ServiceLanding::create($serviceData);

            // Nota: las imágenes pueden ser agregadas luego desde el panel
            // $service->image()->create(['path' => 'landing/services/default.jpg']);
        }

        $this->command->info('✅ Servicios de landing creados con estilo uniforme y texto informativo.');
    }
}
