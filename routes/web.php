<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {

        Route::get('/', function () {
            return redirect()->route('dashboard');
        });
        
        Route::get('/dashboard', [TenantController::class, 'dashboard'])
        ->middleware(['auth', 'verified']); // nuevo nombre
    
        Route::middleware('auth')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            Route::resource('tenants', TenantController::class);    
        });
         // Tenants
          Route::resource('tenants', TenantController::class);

          // Settings
          Route::get('settings',[SettingController::class,'edit'])   ->name('settings.edit');
          Route::put('settings',[SettingController::class,'update'])->name('settings.update');
        
        
        require __DIR__.'/auth.php';
    });
}


