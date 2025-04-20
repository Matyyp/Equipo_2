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
        ] as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        /** ----------------------------------------------------------------
         * Roles
         * ----------------------------------------------------------------*/
        $admin    = Role::firstOrCreate(['name' => 'Admin']);
        $empleado = Role::firstOrCreate(['name' => 'Empleado']);

        /** ----------------------------------------------------------------
         * Asignación de permisos
         * ----------------------------------------------------------------*/
        // Admin = todos
        $admin->syncPermissions(Permission::all());

        // Empleado = sólo ver y editar usuarios
        $empleado->syncPermissions(['users.index']);
    }
}
