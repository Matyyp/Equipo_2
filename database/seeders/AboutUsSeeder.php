<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    public function run()
    {
        if (AboutUs::count() === 0) {
            AboutUs::create([
                // Textos
                'top_text' => 'Nuestra Misión',
                'top_text_active' => true,
                'main_title' => '¿Quiénes Somos?',
                'main_title_active' => true,
                'secondary_text' => 'Somos una empresa dedicada a brindar soluciones efectivas.',
                'secondary_text_active' => true,
                'tertiary_text' => 'Comprometidos con la excelencia.',
                'tertiary_text_active' => true,

                // Botón
                'button_text' => 'Conócenos más',
                'button_link' => '#contact',
                'button_active' => true,

                // Estilo grisáceo
                'button_text_color' => '#f1f5f9',         // gris muy claro
                'button_color' => '#334155',             // slate-700
                'card_color' => '#1e293b',               // slate-800
                'card_text_color' => '#e2e8f0',          // gris claro
                'video_card_color' => '#374151',         // slate-600

                // Videos (ejemplo)
                'video_links' => 'https://www.youtube.com/embed/k2-LUK_vmOA,https://www.youtube.com/embed/zleIaEIBs2M,https://www.youtube.com/embed/Bxo2JkiqG_o',
            ]);

            $this->command->info('✅ About Us creado con estilo grisáceo.');
        } else {
            $this->command->info('ℹ️ Ya existe un registro en About Us. No se creó uno nuevo.');
        }
    }
}
