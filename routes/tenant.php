<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\Maintainers\BusinessController;
use App\Http\Controllers\Tenant\Maintainers\LocationController;
use App\Http\Controllers\Tenant\Maintainers\AccessoryController;
use App\Http\Controllers\Tenant\Maintainers\BrandController;
use App\Http\Controllers\Tenant\Maintainers\ContactinformationController;
use App\Http\Controllers\Tenant\Maintainers\ModelcarController;
use App\Http\Controllers\Tenant\Maintainers\OwnerController;
use App\Http\Controllers\Tenant\Maintainers\RuleController;
use App\Http\Controllers\Tenant\Maintainers\CarController;
use App\Http\Controllers\Tenant\Maintainers\BelongsController;
use App\Http\Controllers\Tenant\Maintainers\BranchOfficeController;


/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/dashboard', function () {
        return view('tenant.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('empresa', BusinessController::class);
        Route::resource('locacion', LocationController::class);
        Route::resource('accesorio', AccessoryController::class);
        Route::resource('informacion_contacto', ContactInformationController::class);
        Route::resource('modelo', ModelcarController::class);
        Route::resource('marca', BrandController::class);
        Route::resource('dueños', OwnerController::class);
        Route::resource('reglas', RuleController::class);

        Route::resource('autos', CarController::class);
        Route::resource('asociado', BelongsController::class);
        Route::resource('sucursales', BranchOfficeController::class);

    });

    require __DIR__.'/auth.php';
});
