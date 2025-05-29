<aside class="main-sidebar sidebar-dark-info elevation-4">
    {{-- Brand --}}
    <a href="{{ route('dashboard') }}"
       class="brand-link d-flex justify-content-center align-items-center">
        @if (! empty($tenantLogo))
            <img
                src="{{ $tenantLogo }}"
                alt="{{ $tenantCompanyName ?? config('app.name') }} logo"
                class="brand-image" 
                style="display:block; margin:0 auto; max-height:50px; width:auto;"
            />
        @else
            <span class="brand-text font-weight-light">
                {{ $tenantCompanyName ?? config('app.name') }}
            </span>
        @endif
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-solid fa-house"></i>
                        <p>Home</p>
                    </a>
                </li>

                {{-- Estacionamiento --}}
                @can('estacionamiento.access')
                <li class="nav-item has-treeview {{ request()->is('estacionamiento*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('estacionamiento*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-square-parking"></i>
                        <p>Estacionamiento<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('estacionamiento.index') }}"
                               class="nav-link {{ request()->routeIs('estacionamiento.index') ? 'active' : '' }}">
                               <i class="fas fa-list nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('estacionamiento.history') }}"
                               class="nav-link {{ request()->routeIs('estacionamiento.history') ? 'active' : '' }}">
                               <i class="fas fa-history nav-icon"></i>
                               
                                <p>Historial</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('carwash.history') }}"
                                class="nav-link {{ request()->routeIs('carwash.history') ? 'active' : '' }}">
                                <i class="fas fa-soap nav-icon"></i>
                                <p>Historial Lavado</p>
                            </a>

                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Reservas --}}
                @can('reservas.access')
                <li class="nav-item has-treeview {{ request()->is('reservations*') || request()->is('rental-cars*') || request()->is('registro-renta*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('reservations*') || request()->is('rental-cars*') || request()->is('registro-renta*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-days"></i>
                        <p>Reservas<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Autos de Arriendo --}}
                        <li class="nav-item">
                            <a href="{{ route('rental-cars.index') }}"
                            class="nav-link {{ request()->routeIs('rental-cars.*') ? 'active' : '' }}">
                                <i class="fas fa-car nav-icon"></i>
                                <p>Autos de Arriendo</p>
                            </a>
                        </li>
                        
                        {{-- Listado de Reservas Web --}}
                        <li class="nav-item">
                            <a href="{{ route('reservations.index') }}"
                            class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listado de Reservas</p>
                            </a>
                        </li>
                        
                        {{-- Listado de Arriendos --}}
                        <li class="nav-item">
                            <a href="{{ route('registro-renta.index') }}"
                            class="nav-link {{ request()->routeIs('registro-renta.*') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Listado de Arriendos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                @can('landing.access')
                <li class="nav-item has-treeview 
                    {{ request()->is('navbar*') || 
                    request()->is('footers*') || 
                    request()->is('hero*') || 
                    request()->is('vehicle*') || 
                    request()->is('quienes-somos*') || 
                    request()->is('map*') || 
                    request()->is('service*') || 
                    request()->is('container-image*') 
                    ? 'menu-open' : '' }}">
                    
                    <a href="#" class="nav-link 
                        {{ request()->is('navbar*') || 
                        request()->is('footers*') || 
                        request()->is('hero*') || 
                        request()->is('vehicle*') || 
                        request()->is('quienes-somos*') || 
                        request()->is('map*') || 
                        request()->is('service*') || 
                        request()->is('container-image*') 
                        ? 'active' : '' }}">
                        
                        <i class="nav-icon fas fa-globe"></i>
                        <p>
                            Contenido Landing
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('landing.navbar.index') }}" class="nav-link {{ request()->routeIs('landing.navbar.*') ? 'active' : '' }}">
                                <i class="fas fa-align-justify nav-icon"></i>
                                <p>Barra de Navegación</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.footer.index') }}" class="nav-link {{ request()->routeIs('landing.footer.*') ? 'active' : '' }}">
                                <i class="fas fa-window-minimize nav-icon"></i>
                                <p>Pie de Página</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.hero.index') }}" class="nav-link {{ request()->routeIs('landing.hero.*') ? 'active' : '' }}">
                                <i class="fas fa-photo-video nav-icon"></i>
                                <p>Sección Hero (Principal)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.vehicle.index') }}" class="nav-link {{ request()->routeIs('landing.vehicle.*') ? 'active' : '' }}">
                                <i class="fas fa-car-alt nav-icon"></i>
                                <p>Vehículos Destacados</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.quienes-somos.index') }}" class="nav-link {{ request()->routeIs('landing.quienes-somos.*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Quiénes Somos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.map.index') }}" class="nav-link {{ request()->routeIs('landing.map.*') ? 'active' : '' }}">
                                <i class="fas fa-map-marked-alt nav-icon"></i>
                                <p>Mapa y Contactos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.service.index') }}" class="nav-link {{ request()->routeIs('landing.service.*') ? 'active' : '' }}">
                                <i class="fas fa-concierge-bell nav-icon"></i>
                                <p>Servicios</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('landing.container-image.index') }}" class="nav-link {{ request()->routeIs('landing.container-image.*') ? 'active' : '' }}">
                                <i class="fas fa-images nav-icon"></i>
                                <p>Carrusel de Imágenes</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan



                {{-- Ventas --}}
                @can('ventas.access')
                <li class="nav-item has-treeview {{ request()->is('pagos*') || request()->is('analiticas*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('pagos*') || request()->is('analiticas*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-wallet"></i>
                        <p>
                            Ventas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payment.index') }}"
                            class="nav-link {{ request()->routeIs('payment.*') ? 'active' : '' }}">
                                <i class="fas fa-store nav-icon"></i>
                                <p>Pagos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('analiticas') }}"
                            class="nav-link {{ request()->routeIs('analiticas*') ? 'active' : '' }}">
                                <i class="fas fa-chart-simple nav-icon"></i>
                                <p>Analíticas</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @endcan
                {{-- Costos de Servicios Básicos --}}
                @can('cost_basic_service.access')
                <li class="nav-item has-treeview {{ request()->is('costos*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('costos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Costos y Servicios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('cost_basic_service.index') }}" 
                               class="nav-link {{ request()->is('costos*') ? 'active' : '' }}">
                               <i class="fa-solid fa-money-bill"></i>
                               <p>Ingresos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cost_basic_service.show') }}" 
                               class="nav-link {{ request()->routeIs('cost_basic_service.show') ? 'active' : '' }}">
                               <i class="fa-solid fa-bag-shopping"></i>
                               <p>Costos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Mantenciones --}}
                @can('mantenimiento.access')
                <li class="nav-item has-treeview {{ request()->is('mantenimiento*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('mantenimiento*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                        <p>Mant. Vehículos<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview"></ul>
                </li>
                @endcan

                {{-- Administración --}}
                @canany(['users.index','roles.index'])
                <li class="nav-item has-treeview 
                    {{ request()->is('users*') || request()->is('roles*') || request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') || request()->is('region*') || request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') || request()->is('reglas*') || request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link 
                        {{ request()->is('users*') || request()->is('roles*') || request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') || request()->is('region*') || request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') || request()->is('reglas*') || request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Administración<i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- Configuración Interna --}}
                        <li class="nav-item has-treeview 
                            {{ request()->is('users*') || request()->is('roles*') || request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') || request()->is('region*') || request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link 
                                {{ request()->is('users*') || request()->is('roles*') || request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') || request()->is('region*') || request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Configuración Interna<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('users.index')
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                        <i class="fas fa-users nav-icon"></i><p>Usuarios</p>
                                    </a>
                                </li>
                                @endcan
                                @role('SuperAdmin')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                        <i class="fas fa-user-shield nav-icon"></i><p>Roles</p>
                                    </a>
                                </li>
                                @endrole
                                @can('mantenedores.access')
                                <li class="nav-item">
                                    <a href="{{ route('empresa.index') }}" class="nav-link {{ request()->routeIs('empresa.index') ? 'active' : '' }}">
                                        <i class="fas fa-briefcase nav-icon"></i><p>Datos Empresa</p>
                                    </a>
                                </li>

                                {{-- Submenú: Ubicación --}}
                                <li class="nav-item has-treeview {{ request()->is('locacion*') || request()->is('region*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('locacion*') || request()->is('region*') ? 'active' : '' }}">
                                        <i class="fas fa-map-marker-alt nav-icon"></i>
                                        <p>Ubicación<i class="right fas fa-angle-left"></i></p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('locacion.index') }}" class="nav-link {{ request()->routeIs('locacion.index') ? 'active' : '' }}">
                                                <p>Locación</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('region.index') }}" class="nav-link {{ request()->routeIs('region.index') ? 'active' : '' }}">
                                                <p>Región</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                {{-- Submenú: Bancos --}}
                                <li class="nav-item has-treeview {{ request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') ? 'active' : '' }}">
                                        <i class="fas fa-university nav-icon"></i>
                                        <p>Bancos<i class="right fas fa-angle-left"></i></p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('banco.index') }}" class="nav-link {{ request()->routeIs('banco.index') ? 'active' : '' }}">
                                                <p>Banco</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('tipo_cuenta.index') }}" class="nav-link {{ request()->routeIs('tipo_cuenta.index') ? 'active' : '' }}">
                                                <p>Tipo de Cuenta</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('cuentas_bancarias.index') }}" class="nav-link {{ request()->routeIs('cuentas_bancarias.index') ? 'active' : '' }}">
                                                <p>Cuentas Bancarias</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('sucursales.index') }}" class="nav-link {{ request()->routeIs('sucursales.index') ? 'active' : '' }}">
                                        <i class="fas fa-store nav-icon"></i><p>Sucursal</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Configuración Contrato --}}
                        <li class="nav-item has-treeview {{ request()->is('reglas*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('reglas*') ? 'active' : '' }}">
                                <i class="fas fa-file-signature nav-icon"></i>
                                <p>Configuración Contrato<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('reglas.index') }}" class="nav-link {{ request()->routeIs('reglas.index') ? 'active' : '' }}">
                                        <p>Reglas de contrato</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Configuración Auto --}}
                        <li class="nav-item has-treeview {{ request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ? 'active' : '' }}">
                                <i class="fas fa-car nav-icon"></i>
                                <p>Configuración Auto<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('autos.index') }}" class="nav-link {{ request()->routeIs('autos.index') ? 'active' : '' }}">
                                        <p>Nuevo auto</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('marca.index') }}" class="nav-link {{ request()->routeIs('marca.index') ? 'active' : '' }}">
                                        <p>Marcas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modelo.index') }}" class="nav-link {{ request()->routeIs('modelo.index') ? 'active' : '' }}">
                                        <p>Modelos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dueños.index') }}" class="nav-link {{ request()->routeIs('dueños.index') ? 'active' : '' }}">
                                        <p>Dueños</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcanany

            </ul>
        </nav>
    </div>
</aside>
