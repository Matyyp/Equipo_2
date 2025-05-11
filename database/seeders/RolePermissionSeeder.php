<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /** ----------------------------------------------------------------
         * Permisos base 
         * ----------------------------------------------------------------*/
        foreach ([
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'mantenedores.access',
            'estacionamiento.access'
        ] as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        /** ----------------------------------------------------------------
         * Roles
         * ----------------------------------------------------------------*/
        $admin    = Role::firstOrCreate(['name' => 'Admin']);
        $user    = Role::firstOrCreate(['name' => 'User']);
        /** ----------------------------------------------------------------
         * Asignación de permisos
         * ----------------------------------------------------------------*/

        $admin->syncPermissions(Permission::all());


    }
}
