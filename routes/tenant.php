<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\{ProfileController, UserController, RoleController};

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
    Route::get('/', fn () => view('welcome'));

    Route::get('/dashboard', fn () => view('tenant.admin.dashboard'))
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get   ('/profile', [ProfileController::class, 'edit'   ])->name('profile.edit');
        Route::patch ('/profile', [ProfileController::class, 'update' ])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // CRUD Usuarios
    Route::middleware(['auth', 'permission:users.index'])->group(function () {
        Route::get('users', [UserController::class, 'index'])
            ->name('users.index');
    });

    Route::middleware(['auth', 'permission:users.create'])->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users',        [UserController::class, 'store' ])->name('users.store');
    });

    Route::middleware(['auth', 'permission:users.edit'])->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit']  )->name('users.edit');
        Route::put('users/{user}',      [UserController::class, 'update'])->name('users.update');
    });

    Route::middleware(['auth', 'permission:users.delete'])->group(function () {
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware(['auth', 'role:Admin'])->group(function () {
        Route::resource('roles', RoleController::class)
        ->only(['index','create','store','edit','update','destroy']);
        
    });
    require __DIR__.'/auth.php';
});