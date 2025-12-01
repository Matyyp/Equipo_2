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
use App\Http\Controllers\Tenant\CarWash\CarWashController;
use App\Http\Controllers\Tenant\Cost\CostBasicServiceController;
use App\Http\Controllers\Tenant\Maintainers\WorkerController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\RentalCarController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ServiceLandingController;
use App\Http\Controllers\ContainerImageLandingController;
use App\Http\Controllers\AccidentController;
use App\Http\Controllers\WhatsAppController;

use App\Http\Controllers\UserRatingController;


use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TransbankController;
use App\Http\Controllers\RegisterRentController;

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
    // Rutas de la landing page
    Route::get('/', function () {
        return view('tenant.landings.welcomev2');
    });
    Route::get('/whatsapp/contract-link/{parkingRegister}', [ParkingController::class, 'generateContractWhatsappLink'])
    ->name('whatsapp.contract.link');


    Route::get('/contrato/{parking}/print', [ParkingController::class, 'print'])
        ->name('contrato.print')
        ->middleware(['signed', 'throttle:5,10']);

    Route::get('/available‐cars', [LandingController::class, 'availableCars'])
     ->name('landings.available');

    Route::get('landings/available/cars', [LandingController::class, 'availableCarsPartial'])
     ->name('landings.available.partial');

    Route::middleware('auth')->group(function() {
        Route::get('cars/{car}/reserve', [LandingController::class,'reserve'])
            ->name('cars.reserve');

    });

Route::middleware(['auth', 'permission:admin.panel.access'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'mantencionesAlert'])->name('dashboard');
    // Dashboard principal vía controlador
    //Route::get('/analiticas', [DashboardController::class, 'index'])->name('analiticas');
    //Route::get('/analiticas/chart-data', [DashboardController::class, 'chartData'])->name('analiticas.chart.data');
    Route::get('dashboard/rents/data', [DashboardController::class, 'getRentsDataDashboard'])->name('dashboard.rents.data');

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
        Route::get('/servicios/create/{sucursalId}', [ServicioController::class, 'create'])->name('servicios.create');
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
        Route::delete('estacionamiento/extra/{addon}', [ParkingController::class, 'removeAddon'])
        ->name('estacionamiento.extra.remove');
        Route::post('/estacionamiento/{id}/renew', [ParkingController::class, 'renew'])
        ->name('estacionamiento.renew');
        Route::get('estacionamiento/extra-services', [ParkingController::class, 'getExtraServices']);
        Route::post('estacionamiento/{id}/update-extra-services', [ParkingController::class, 'updateExtraServices']);
        Route::get('estacionamiento/servicios-por-sucursal', [ParkingController::class, 'getServicesByBranch'])
        ->name('estacionamiento.getServicesByBranch');
        Route::get('estacionamiento/search', [ParkingController::class, 'search'])
        ->name('estacionamiento.search');
        Route::get('estacionamiento/search-phone', [ParkingController::class, 'searchPhone'])
        ->name('estacionamiento.searchPhone');
        Route::get('estacionamiento/history', [ParkingController::class, 'history'])
        ->name('estacionamiento.history');
        Route::post('estacionamiento/tickets/print', [ParkingController::class, 'printTicket'])
        ->name('tickets.print');
        Route::get('parking/{parking}/send-whatsapp', [ParkingController::class, 'sendContractWhatsApp'])
        ->name('parking.send_whatsapp');
        Route::get('estacionamiento/tickets', [ParkingController::class, 'infoTicket'])
        ->name('estacionamiento.ticket');
        Route::get('/admin/whatsapp', [WhatsAppController::class, 'dashboard'])->name('whatsapp.dashboard');
        Route::post('/admin/whatsapp/logout', [WhatsAppController::class, 'logout'])->name('whatsapp.logout');
        Route::post('/admin/whatsapp/send', [WhatsAppController::class, 'send'])->name('whatsapp.send');
        


        Route::resource('estacionamiento', ParkingController::class);
        Route::post('/estacionamiento/{parking}/checkout', [ParkingController::class, 'checkout'])->name('estacionamiento.checkout');
        Route::get('/payment/{id}/voucher', [PaymentRecordController::class, 'downloadPdf'])->name('payment.record');

    });
    // módulo lavado
    Route::middleware(['auth', 'permission:carwash.access'])->group(function () {
        Route::resource('lavados', CarWashController::class);
        Route::patch('lavados/{id}/disable', [CarWashController::class, 'disable'])->name('lavados.disable');
    
        //Ruta AJAX para obtener tipos de lavado por sucursal
        Route::get('lavados-sucursal', [CarWashController::class, 'getByBranch'])->name('lavados.sucursal');
        Route::get('carwash/history', [CarWashController::class, 'history'])->name('carwash.history');
        Route::patch('carwash/marcar-lavado/{id}', [CarWashController::class, 'markAsWashed'])->name('carwash.markAsWashed');
    });
    // módulo accidentes
    Route::middleware(['auth', 'permission:accidente.access'])->group(function () {
        // CRUD clásico
        Route::get('accidente',         [AccidentController::class, 'index'])->name('accidente.index');
        Route::get('accidente/data',    [AccidentController::class, 'data'])->name('accidente.data');
        Route::get('accidente/create',  [AccidentController::class, 'create'])->name('accidente.create');
        Route::post('accidente',        [AccidentController::class, 'store'])->name('accidente.store');
        Route::get('accidente/{accidente}/edit', [AccidentController::class, 'edit'])->name('accidente.edit');
        Route::put('accidente/{accidente}', [AccidentController::class, 'update'])->name('accidente.update');
        Route::delete('accidente/{accidente}', [AccidentController::class, 'destroy'])->name('accidente.destroy');
        Route::post('accidente/{accidente}/complete', [AccidentController::class, 'markComplete'])->name('accidente.markComplete');
        Route::get('accidente/{accidente}/pdf', [AccidentController::class, 'downloadPdf'])->name('accidente.downloadPdf');
        Route::delete('accidente/{accidente}/photo/{photo}', [\App\Http\Controllers\AccidentController::class, 'destroyPhoto'])->name('accidente.photo.destroy');
    });


    // Modulo ventas
    Route::middleware(['auth', 'permission:ventas.access'])->group(function () {
        Route::resource('pagos', PaymentRecordController::class)->names('payment');
        
        // Dashboard Analíticas
        Route::get('/analiticas', [DashboardController::class, 'index'])->name('analiticas');
        Route::get('/analiticas/chart-data', [DashboardController::class, 'chartData'])->name('analiticas.chart.data');
        Route::get('/analiticas/chart-line-data', [DashboardController::class, 'chartLineData'])->name('analiticas.chart.line.data');
        Route::get('/analiticas/car-type-ranking', [DashboardController::class, 'carTypeRanking'])->name('analiticas.car.type.ranking');
        Route::get('/analiticas/top-users-ranking', [DashboardController::class, 'topUsersRanking'])->name('analiticas.top.users.ranking');
        Route::get('/analiticas/user-ratings/{client_rut}', [DashboardController::class, 'userRatings'])->name('analiticas.user.ratings');
    });
    // Módulo Costos de Servicios Básicos 
    Route::middleware(['auth', 'permission:cost_basic_service.access'])->group(function () {
        Route::get('cost', [CostBasicServiceController::class, 'index'])->name('cost_basic_service.index');
        Route::get('cost/ingresos/data', [CostBasicServiceController::class, 'ingresosData'])->name('cost_basic_service.ingresos.data');
        Route::get('cost/create', [CostBasicServiceController::class, 'create'])->name('cost_basic_service.create');
        Route::get('costos/show', [CostBasicServiceController::class, 'showPage'])->name('cost_basic_service.show');
        Route::get('costos/data', [CostBasicServiceController::class, 'data'])->name('cost_basic_service.data');
        Route::post('costos/store', [CostBasicServiceController::class, 'store'])->name('cost_basic_service.store');
        Route::get('costos/{id}/edit', [CostBasicServiceController::class, 'edit'])->name('cost_basic_service.edit');
        Route::put('costos/{id}', [CostBasicServiceController::class, 'update'])->name('cost_basic_service.update');
        Route::delete('costos/{id}', [CostBasicServiceController::class, 'destroy']);
        
    });


    //transbank
    Route::post('/webpay/init/{car}', [TransbankController::class, 'init'])->name('webpay.init');
    Route::get('/webpay/confirm', [TransbankController::class, 'confirm'])->name('webpay.confirm');
    
    // Modulo de reservas
    Route::middleware(['auth', 'permission:reservas.access'])->group(function () {
        Route::get('rental-cars/data', [RentalCarController::class, 'data'])
        ->name('rental-cars.data');
        Route::resource('rental-cars', RentalCarController::class);
        
        Route::get('reservations/data', [ReservationController::class, 'data'])
            ->name('reservations.data');

        Route::get('reservations', [ReservationController::class, 'index'])
            ->name('reservations.index');

        Route::post('reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])
            ->name('reservations.confirm');
        
        // Cancelar una reserva
        Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
            ->name('reservations.cancel');

        Route::prefix('reservas')->name('reservas.')->group(function () {
            Route::post('{reservation}/confirmar', [ReservationController::class, 'confirm'])->name('confirmar');
            Route::post('{reservation}/cancelar', [ReservationController::class, 'cancel'])->name('cancelar');

            Route::get('{reservation}/crear-registro-renta', [ReservationController::class, 'crearRegistroRenta'])->name('crearRegistroRenta');
            Route::post('{reservation}/guardar-registro-renta', [ReservationController::class, 'guardarRegistroRenta'])->name('guardarRegistroRenta');
        });
    });
    // Modulo de arriendos
    Route::middleware(['auth', 'permission:arriendos.access'])->group(function () {
        Route::get('registro-renta/data', [RegisterRentController::class, 'data'])->name('registro_renta.data');
        Route::resource('registro-renta', RegisterRentController::class);
        Route::resource('user_ratings', UserRatingController::class)->only(['store']);
        Route::get('/user_ratings/{user}', [UserRatingController::class, 'getByUser']);
        Route::post('registro-renta', [RegisterRentController::class, 'store'])->name('registro-renta.store');
        Route::get('registro-renta/fechas-ocupadas/{id}', [RegisterRentController::class, 'fechasOcupadas']);
        Route::get('/buscar-cliente', [RegisterRentController::class, 'buscarClientePorCorreo']);
        Route::put('registro-renta/completar/{id}', [RegisterRentController::class, 'completar'])->name('registro-renta.completar');
        Route::get('register_rents/{id}/contrato', [RegisterRentController::class, 'contratoPDF'])->name('register_rents.contrato_pdf');

    });
    
    //Landing
    Route::middleware(['auth', 'permission:landing.access'])->group(function () {
        Route::get('navbar/data', [NavbarController::class, 'data'])->name('landing.navbar.data');

        Route::resource('navbar', NavbarController::class)->only(['index', 'edit', 'update'])->names([
            'index' => 'landing.navbar.index',
            'edit' => 'landing.navbar.edit',
            'update' => 'landing.navbar.update',
        ]);

        Route::resource('footers', FooterController::class)->only(['index', 'edit', 'update'])->names([
            'index' => 'landing.footer.index',
            'edit' => 'landing.footer.edit',
            'update' => 'landing.footer.update',
        ]);

        Route::get('hero/data', [App\Http\Controllers\HeroController::class, 'data'])->name('landing.hero.data');

        Route::resource('hero', App\Http\Controllers\HeroController::class)->names([
            'index' => 'landing.hero.index',
            'create' => 'landing.hero.create',
            'store' => 'landing.hero.store',
            'edit' => 'landing.hero.edit',
            'update' => 'landing.hero.update',
            'destroy' => 'landing.hero.destroy',
        ]);
        Route::get('vehicle/data', [VehicleTypeController::class, 'data'])->name('landing.vehicle.data');

        Route::resource('vehicle', VehicleTypeController::class)->names([
            'index' => 'landing.vehicle.index',
            'create' => 'landing.vehicle.create',
            'store' => 'landing.vehicle.store',
            'edit' => 'landing.vehicle.edit',
            'update' => 'landing.vehicle.update',
            'destroy' => 'landing.vehicle.destroy',
        ]);
        Route::get('quienes-somos/data', [AboutUsController::class, 'data'])->name('landing.quienes-somos.data');

        Route::prefix('quienes-somos')->group(function () {
            Route::get('/', [AboutUsController::class, 'index'])->name('landing.quienes-somos.index');
            Route::get('/edit/{id}', [AboutUsController::class, 'edit'])->name('landing.quienes-somos.edit');
            Route::put('/update/{id}', [AboutUsController::class, 'update'])->name('landing.quienes-somos.update');
        });
        Route::resource('map', MapController::class)->names([
        'index' => 'landing.map.index',
        'create' => 'landing.map.create',
        'store' => 'landing.map.store',
        'edit' => 'landing.map.edit',
        'update' => 'landing.map.update',
        'destroy' => 'landing.map.destroy',
            ]);
    // Rutas para Service Landing
        Route::group(['prefix' => 'service'], function() {
            Route::get('data', [ServiceLandingController::class, 'data'])->name('landing.service.data');
            
            Route::resource('/', ServiceLandingController::class)
                ->parameters(['' => 'serviceLanding'])
                ->names([
                    'index' => 'landing.service.index',
                    'create' => 'landing.service.create',
                    'store' => 'landing.service.store',
                    'edit' => 'landing.service.edit',
                    'update' => 'landing.service.update',
                    'destroy' => 'landing.service.destroy',
                ]);
        });

    Route::get('container-image/data', [ContainerImageLandingController::class, 'data'])
        ->name('landing.container-image.data');
        // Ruta para DataTables
        Route::get('map/data', [MapController::class, 'data'])->name('landing.map.data');
        Route::resource('container-image', ContainerImageLandingController::class)->names([
        'index' => 'landing.container-image.index',
        'create' => 'landing.container-image.create',
        'store' => 'landing.container-image.store',
        'edit' => 'landing.container-image.edit',
        'update' => 'landing.container-image.update',
        'destroy' => 'landing.container-image.destroy',
        ]);
        // Agrega esta ruta adicional para DataTables

    });
    // Módulo de mantenimiento
    Route::middleware(['auth', 'permission:landing.access'])->prefix('maintenance')->group(function () {
        Route::post('entries/{entry}/mark-unavailable', [\App\Http\Controllers\MaintenanceController::class, 'markUnavailable'])
        ->name('maintenance.entries.mark-unavailable');
        Route::put('entries/{entry}/complete', [\App\Http\Controllers\MaintenanceController::class, 'markCompleted'])
            ->name('maintenance.entries.complete');
        Route::post('entries/schedule', [\App\Http\Controllers\MaintenanceController::class, 'storeScheduled'])
            ->name('maintenance.entries.schedule');
        Route::post('entries/interrupt', [\App\Http\Controllers\MaintenanceController::class, 'interruptScheduled'])
            ->name('maintenance.entries.interrupt');

        //Tipos de mantención (maintenance_types)
        Route::get('type/data', [\App\Http\Controllers\MaintenanceTypeController::class, 'data'])
            ->name('maintenance.type.data');

        Route::resource('type', \App\Http\Controllers\MaintenanceTypeController::class)
            ->names([
                'index'   => 'maintenance.type.index',
                'create'  => 'maintenance.type.create',
                'store'   => 'maintenance.type.store',
                'edit'    => 'maintenance.type.edit',
                'update'  => 'maintenance.type.update',
                'destroy' => 'maintenance.type.destroy',
            ]);

        // Mantenciones (entries / maintenances)
        Route::get('entries/data', [\App\Http\Controllers\MaintenanceController::class, 'data'])
            ->name('maintenance.entries.data');

        Route::resource('entries', \App\Http\Controllers\MaintenanceController::class)
    ->parameters(['entries' => 'maintenance'])
    ->names([
        'index'   => 'maintenance.entries.index',
        'create'  => 'maintenance.entries.create',
        'store'   => 'maintenance.entries.store',
        'edit'    => 'maintenance.entries.edit',
        'update'  => 'maintenance.entries.update',
        'destroy' => 'maintenance.entries.destroy',
    ]);

    });

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