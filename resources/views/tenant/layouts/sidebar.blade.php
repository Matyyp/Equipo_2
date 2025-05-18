<aside class="main-sidebar sidebar-dark-primary elevation-4">
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
                                <p>Listado</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('estacionamiento.history') }}"
                               class="nav-link {{ request()->routeIs('estacionamiento.history') ? 'active' : '' }}">
                                <p>Historial</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Reservas --}}
                @can('reservas.access')
                <li class="nav-item has-treeview {{ request()->is('reservas*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('reservas*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-calendar-days"></i>
                        <p>Reservas<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('rental-cars.index') }}"
                            class="nav-link {{ request()->routeIs('rental-cars.*') ? 'active' : '' }}">
                                <i class="fas fa-car nav-icon"></i>
                                <p>Autos de Arriendo</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Ventas --}}
                @can('ventas.access')
                <li class="nav-item has-treeview {{ request()->is('ventas*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('ventas*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-wallet"></i>
                        <p>Ventas<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payment.index') }}"
                               class="nav-link {{ request()->routeIs('payment.index') ? 'active' : '' }}">
                                <i class="fas fa-store nav-icon"></i>
                                <p>Pagos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('analiticas') }}"
                               class="nav-link {{ request()->routeIs('analiticas') ? 'active' : '' }}">
                                <i class="fas fa-chart-simple nav-icon"></i>
                                <p>Analiticas</p>
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
                <li class="nav-item has-treeview {{ request()->is('administracion*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('administracion*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Administración<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Configuración Interna --}}
                        <li class="nav-item has-treeview
                            {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ||
                               request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') ||
                               request()->is('region*') || request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link
                                {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ||
                                   request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') ||
                                   request()->is('region*') || request()->is('banco*') || request()->is('tipo_cuenta*') || request()->is('cuentas_bancarias*') ? 'active' : '' }}">
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
                                <p>Configuracion contrato<i class="right fas fa-angle-left"></i></p>
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

                    </ul>
                </li>
                @endcanany

            </ul>
        </nav>
    </div>
</aside>
