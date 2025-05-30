<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrador',
                'password' => bcrypt('12341234'),
                'id_branch_office' => '1'
            ]
        );

        $user->assignRole('SuperAdmin');
    }
}
