<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;  
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;

class SetupTenantJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $tenant;
    protected $email;

    public function __construct($tenant, string $email)
    {
        $this->tenant = $tenant;
        $this->email  = $email;
    }

    public function handle(): void
    {
        // Inicializa el contexto del tenant
        tenancy()->initialize($this->tenant);

        // 2) Seed de roles y permisos
        Artisan::call('db:seed', [
            '--class' => \Database\Seeders\DatabaseSeeder::class,
            '--force' => true,
        ]);

        // 3) Crear usuario admin y asignar rol
        $temporaryPassword = Str::random(12);
        $user = User::create([
            'name'     => 'Administrador',
            'email'    => $this->email,
            'password' => Hash::make($temporaryPassword),
        ]);
        $user->assignRole('admin');  // ahora sí existe

        // 4) Forzar dominio de tenant para que el reset link apunte bien
        $domain = $this->tenant->domains->first()->domain;
        $scheme = str_starts_with($domain, 'http') ? '' : 'https://';
        URL::forceRootUrl($scheme . $domain);
        URL::forceScheme('https');

        // 5) Enviar reset link vía Password Broker (Breeze)
        Password::broker()->sendResetLink([
            'email' => $this->email,
        ]);

        // 6) Finaliza el contexto
        tenancy()->end();
    }
}
