<?php

declare(strict_types=1);

namespace App\Providers;
use Stancl\Tenancy\Events\TenancyBootstrapped;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;
use App\Models\Business;
use Illuminate\Support\Facades\Schema;



class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = '';

    public function events()
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,
                    Jobs\MigrateDatabase::class,
                    // Jobs\SeedDatabase::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!

                ])->send(function (Events\TenantCreated $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [
                JobPipeline::make([
                    Jobs\DeleteDatabase::class,
                ])->send(function (Events\TenantDeleted $event) {
                    return $event->tenant;
                })->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],

            // Resource syncing
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],

            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->bootEvents();
        $this->mapRoutes();

        $this->makeTenancyMiddlewareHighestPriority();

        $this->app['events']->listen(TenancyBootstrapped::class, function () {
            // toma el View Finder y añade tu carpeta al inicio
            View::getFacadeRoot()      // devuelve el Factory
                ->getFinder()          // devuelve el FileViewFinder
                ->prependLocation(     // aquí sí existe
                    resource_path('views/tenant')
                );
        });

        $this->app['events']->listen(TenancyBootstrapped::class, function () {
            $logoUrl         = null;
            $companyName     = null;
            $companyFunds    = null; // ← DECLARACIÓN AQUÍ
            $branchName      = null;

            if (Schema::hasTable('businesses')) {
                $business = Business::first();

                if ($business) {
                    $host         = request()->getHost();
                    $logoUrl      = $business->logo ? tenant_asset($business->logo) : null;
                    $companyName  = $business->name_business;
                    $companyFunds = $business->funds
                    ? tenant_asset($business->funds)
                    : null;

                    $host    = request()->getHost();
                    $logoUrl = $business->logo
                        ? tenant_asset($business->logo)
                        : null;

                    $companyName = $business->name_business;
                }
            }

            // Diccionario de traducción
            $replacements = [
                'index' => 'Listado',
                'create' => 'Crear',
                'edit' => 'Editar',
                'show' => 'Detalle',
                // puedes agregar más términos aquí
            ];

            try {
                $routeName = Route::currentRouteName(); // ej. "usuarios.index"

                $branchName = $routeName ? collect(explode('.', $routeName))
                    ->map(function ($segment) use ($replacements) {
                        return $replacements[$segment] ?? ucfirst($segment);
                    })
                    ->implode(' > ') : 'Inicio';
            } catch (\Throwable $e) {
                $branchName = 'Inicio';
            }

            View::share('tenantLogo', $logoUrl);
            View::share('tenantCompanyName', $companyName);
            View::share('tenantFunds', $companyFunds); // ← YA NO LANZA ERROR
            View::share('tenantBranchName', $branchName);
        });

    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    protected function mapRoutes()
    {
        $this->app->booted(function () {
            if (file_exists(base_path('routes/tenant.php'))) {
                Route::namespace(static::$controllerNamespace)
                    ->group(base_path('routes/tenant.php'));
            }
        });
    }

    protected function makeTenancyMiddlewareHighestPriority()
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            Middleware\PreventAccessFromCentralDomains::class,

            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}
