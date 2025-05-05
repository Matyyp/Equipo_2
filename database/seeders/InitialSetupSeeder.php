<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InitialSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Tabla banks
        $banks = [
            'Banco de Chile', 'Banco Santander', 'Banco Estado', 'Banco BCI', 'Scotiabank',
            'Banco Itaú', 'Banco Security', 'Banco Falabella', 'Banco Ripley', 'Banco Consorcio',
            'Banco Internacional', 'Banco BICE', 'HSBC Bank (Chile)', 'Banco BTG Pactual', 'Banco Do Brasil'
        ];

        foreach ($banks as $name) {
            DB::table('banks')->insert([
                'name_bank' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Tabla type_accounts
        $types = ['Cuenta Corriente', 'Cuenta Vista', 'Cuenta de Ahorro', 'Cuenta RUT', 'Cuenta Empresarial'];
        foreach ($types as $type) {
            DB::table('type_accounts')->insert([
                'name_type_account' => $type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Tabla model_cars (solo nombres, relación con marcas es externa)
        $models = ['Yaris', 'Corolla', 'Hilux', 'Civic', 'Versa', 'Sentra', 'CX-5', 'Vitara', 'Swift', 'Sail'];
        foreach ($models as $model) {
            DB::table('model_cars')->insert([
                'name_model' => $model,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Tabla brand_cars
        $brands = ['Toyota', 'Nissan', 'Hyundai', 'Kia', 'Chevrolet', 'Suzuki', 'Ford', 'Mazda', 'Volkswagen', 'Peugeot'];
        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name_brand' => $brand,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Tabla regions
        $regions = [
            'Región de Arica y Parinacota', 'Región de Tarapacá', 'Región de Antofagasta', 'Región de Atacama',
            'Región de Coquimbo', 'Región de Valparaíso', 'Región Metropolitana de Santiago'
        ];
        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name_region' => $region,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Tabla locations por región
        $locationsByRegion = [
            'Región de Arica y Parinacota' => ['Arica', 'Camarones', 'Putre', 'General Lagos'],
            'Región de Tarapacá' => ['Iquique', 'Alto Hospicio', 'Pozo Almonte', 'Camiña', 'Colchane', 'Huara', 'Pica'],
            'Región de Antofagasta' => ['Antofagasta', 'Mejillones', 'Sierra Gorda', 'Taltal', 'Calama', 'Ollagüe', 'San Pedro de Atacama', 'Tocopilla', 'María Elena'],
            'Región de Atacama' => ['Copiapó', 'Caldera', 'Tierra Amarilla', 'Chañaral', 'Diego de Almagro', 'Vallenar', 'Freirina', 'Huasco', 'Alto del Carmen'],
            'Región de Coquimbo' => ['La Serena', 'Coquimbo', 'Andacollo', 'La Higuera', 'Paihuano', 'Vicuña', 'Illapel', 'Canela', 'Los Vilos', 'Salamanca', 'Ovalle', 'Combarbalá', 'Monte Patria', 'Punitaqui', 'Río Hurtado'],
            'Región de Valparaíso' => ['Valparaíso', 'Casablanca', 'Concón', 'Juan Fernández', 'Puchuncaví', 'Quintero', 'Viña del Mar', 'Isla de Pascua', 'Los Andes', 'Calle Larga', 'Rinconada', 'San Esteban', 'La Ligua', 'Cabildo', 'Papudo', 'Petorca', 'Zapallar', 'Quillota', 'La Calera', 'Hijuelas', 'La Cruz', 'Nogales', 'San Antonio', 'Algarrobo', 'Cartagena', 'El Quisco', 'El Tabo', 'Santo Domingo', 'San Felipe', 'Catemu', 'Llaillay', 'Panquehue', 'Putaendo'],
            'Región Metropolitana de Santiago' => ['Santiago', 'Cerrillos', 'Cerro Navia', 'Conchalí', 'El Bosque', 'Estación Central', 'Huechuraba', 'Independencia', 'La Cisterna', 'La Florida', 'La Granja', 'La Pintana', 'La Reina', 'Las Condes', 'Lo Barnechea', 'Lo Espejo', 'Lo Prado', 'Macul', 'Maipú', 'Nuñoa', 'Pedro Aguirre Cerda', 'Peñalolén', 'Providencia', 'Pudahuel', 'Quilicura', 'Quinta Normal', 'Recoleta', 'Renca', 'San Joaquín', 'San Miguel', 'San Ramón', 'Vitacura', 'Puente Alto', 'Pirque', 'San José de Maipo', 'Colina', 'Lampa', 'Tiltil', 'San Bernardo', 'Buin', 'Calera de Tango', 'Paine', 'Melipilla', 'Alhué', 'Curacaví', 'María Pinto', 'San Pedro', 'Talagante', 'El Monte', 'Isla de Maipo', 'Padre Hurtado', 'Peñaflor']
        ];

        foreach ($locationsByRegion as $regionName => $communes) {
            $regionId = DB::table('regions')->where('name_region', $regionName)->value('id');
            if ($regionId) {
                foreach ($communes as $commune) {
                    DB::table('locations')->insert([
                        'commune' => $commune,
                        'id_region' => $regionId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
