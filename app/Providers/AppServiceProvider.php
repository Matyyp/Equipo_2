<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solo si ya existe la tabla 'settings'
        if (Schema::hasTable('settings')) {
            // Obtiene los valores actuales
            $email = Setting::get('company_email', config('mail.from.address'));
            $name  = Setting::get('company_name',  config('mail.from.name'));

            // Sobrescribe la config de Laravel
            config([
                'mail.from.address' => $email,
                'mail.from.name'    => $name,
            ]);
        }
    }
}
