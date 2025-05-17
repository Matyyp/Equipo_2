<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\{ProfileController, UserController, RoleController};
use App\Http\Controllers\Tenant\Maintainers\BusinessController;
use App\Http\Controllers\Tenant\Maintainers\LocationController;
use App\Http\Controllers\Tenant\Maintainers\AccessoryController;
use App\Http\Controllers\Tenant\Maintainers\BrandController;
use App\Http\Controllers\Tenant\Maintainers\ContactInformationController;
use App\Http\Controllers\Tenant\Maintainers\ModelcarController;
use App\Http\Controllers\Tenant\Maintainers\OwnerController;
use App\Http\Controllers\Tenant\Maintainers\RuleController;
use App\Http\Controllers\Tenant\Maintainers\CarController;
use App\Http\Controllers\Tenant\Maintainers\BelongsController;
use App\Http\Controllers\Tenant\Maintainers\BranchOfficeController;
use App\Http\Controllers\Tenant\Maintainers\ServiceController;
use App\Http\Controllers\Tenant\Maintainers\ContractController;
use App\Http\Controllers\Tenant\Maintainers\RegionController;
use App\Http\Controllers\Tenant\Maintainers\BankController;
use App\Http\Controllers\Tenant\Maintainers\TypeAccountController;
use App\Http\Controllers\Tenant\Parking\ParkingController;
use App\Http\Controllers\Tenant\Maintainers\PaymentRecordController;
use App\Http\Controllers\Tenant\Maintainers\BankDetailController;
use App\Http\Controllers\Tenant\Dashboard\DashboardController;
use App\Http\Controllers\Tenant\Maintainers\WorkerController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\RentalCarController;

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
        return view('tenant.landings.welcome');
    });
    //aca agregar todos los roles que necesiten entran al panel de admin
    Route::middleware(['auth', 'permission:admin.panel.access'])->group(function () {
        Route::get('/dashboard', function () {
            return view('tenant.admin.dashboard');
        })->name('dashboard');
        // Dashboard principal vía controlador
        //Route::get('/analiticas', [DashboardController::class, 'index'])->name('analiticas');

        //Route::get('/analiticas/chart-data', [DashboardController::class, 'chartData'])->name('analiticas.chart.data');
    });
    
    Route::middleware('auth')->group(function () {
        Route::get   ('/profile', [ProfileController::class, 'edit'   ])->name('profile.edit');
        Route::patch ('/profile', [ProfileController::class, 'update' ])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    });
    
    //Mantenedores
    Route::middleware(['auth', 'permission:mantenedores.access'])->group(function () {
        Route::resource('empresa', BusinessController::class);

        Route::get('locacion/data', [LocationController::class, 'data'])->name('locacion.data');
        Route::resource('locacion', LocationController::class);
        
        Route::resource('accesorio', AccessoryController::class);
        Route::get('informacion_contacto/create/{branch}', [ContactInformationController::class, 'create'])->name('informacion_contacto.create');
        Route::resource('informacion_contacto', ContactInformationController::class)->except(['create']);
        Route::resource('modelo', ModelcarController::class);
        Route::resource('marca', BrandController::class);
        Route::resource('dueños', OwnerController::class);
        Route::resource('reglas', RuleController::class);

        Route::resource('autos', CarController::class);
        Route::resource('asociado', BelongsController::class);
        Route::get('/verificar-sucursal', [BranchOfficeController::class, 'verificarSucursalExistente'])->name('sucursal.verificar');
        Route::resource('sucursales', BranchOfficeController::class);
        Route::resource('servicios', ServiceController::class);
        Route::patch('/servicios/{id}/disable', [ServiceController::class, 'disable'])->name('servicios.disable');
        Route::get('contratos/create/{branch}/{type}', [ContractController::class, 'create'])->name('contratos.create');
        Route::resource('contratos', ContractController::class);
        //Route::resource('pagos', PaymentRecordController::class)->names('payment');

        Route::get('region/data', [RegionController::class, 'index'])->name('region.data');
        Route::resource('region', RegionController::class);
        Route::get('banco/data', [BankController::class, 'data'])->name('banco.data');
        Route::resource('banco', BankController::class);
        Route::get('tipo_cuenta/data', [TypeAccountController::class, 'data'])->name('tipo_cuenta.data');
        Route::resource('tipo_cuenta', TypeAccountController::class);
        Route::get('cuentas_bancarias/data', [BankDetailController::class, 'data'])->name('cuentas_bancarias.data');
        Route::resource('cuentas_bancarias', BankDetailController::class);
        Route::resource('trabajadores', WorkerController::class);

    });
    // Modulo estacionamiento
    Route::middleware(['auth', 'permission:estacionamiento.access'])->group(function () {

        Route::get('estacionamiento/check-contrato', [ParkingController::class, 'checkContrato'])->name('estacionamiento.checkContrato');
        Route::get('estacionamiento/data', [ParkingController::class, 'data'])
        ->name('estacionamiento.data');
        Route::get('estacionamiento/servicios-por-sucursal', [ParkingController::class, 'getServicesByBranch'])
        ->name('estacionamiento.getServicesByBranch');
        Route::get('estacionamiento/search', [ParkingController::class, 'search'])
        ->name('estacionamiento.search');
        Route::get('estacionamiento/search-phone', [ParkingController::class, 'searchPhone'])
        ->name('estacionamiento.searchPhone');
        Route::get('estacionamiento/history', [ParkingController::class, 'history'])
        ->name('estacionamiento.history');
        Route::get('/contrato/{parking}/print', [ParkingController::class, 'print'])
        ->name('contrato.print');
        Route::get('/ticket/{parking}/print', [ParkingController::class, 'printTicket'])
        ->name('contrato.print');

        Route::resource('estacionamiento', ParkingController::class);
        Route::post('/estacionamiento/{parking}/checkout', [ParkingController::class, 'checkout'])->name('estacionamiento.checkout');
        Route::resource('estacionamiento', ParkingController::class);
        Route::get('/payment/{id}/voucher', [PaymentRecordController::class, 'downloadPdf'])->name('payment.record');

    });
    // Modulo ventas
    Route::middleware(['auth', 'permission:ventas.access'])->group(function () {
        Route::resource('pagos', PaymentRecordController::class)->names('payment');
        Route::get('/analiticas', [DashboardController::class, 'index'])->name('analiticas');
        Route::get('/analiticas/chart-data', [DashboardController::class, 'chartData'])->name('analiticas.chart.data');
    });
    
    Route::resource('rental-cars', RentalCarController::class);

    // CRUD Usuarios
    Route::middleware(['auth', 'permission:users.index'])->group(function () {
        // Listado principal
        Route::get('users',      [UserController::class, 'index'])->name('users.index');
        // AJAX DataTables
        Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
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

    Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
        Route::get('roles/data', [RoleController::class, 'getData'])
            ->name('roles.data');

        Route::resource('roles', RoleController::class)
            ->only(['index','create','store','edit','update','destroy']);
        
    });
    require __DIR__.'/auth.php';
});