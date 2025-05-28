<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            InitialSetupSeeder::class,
            NavbarSeeder::class,
            FooterSeeder::class,
            AboutUsSeeder::class,
            MapSeeder::class,
            ServiceLandingSeeder::class,
        ]);
    }
}