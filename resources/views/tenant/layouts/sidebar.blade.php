<aside class="main-sidebar sidebar-dark-primary elevation-4">
    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">Rent a Car</span>
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                {{-- Dashboard siempre visible --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Estacionamiento: aparecerá si se tienen los permisos dentro del canany tambien se puede usar @role y endrole--}}
                <!-- @canany(['users.index','users.create']) -->
                <li class="nav-item has-treeview {{ request()->is('estacionamiento*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('estacionamiento*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-square-parking"></i>
                        <p>
                            Estacionamiento
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- ejemplo de como agregar un item al sidebar -->

                        <!-- {{-- Listado --}}
                        @can('users.index')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                               class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        @endcan -->

                    </ul>
                </li>
                <!-- @endcanany -->

                {{-- Reservas --}}
                <!-- @canany(['users.index','users.create']) -->
                <li class="nav-item has-treeview {{ request()->is('reservas*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('reservas*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-calendar-days"></i>
                        <p>
                            Reservas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- aca agregar vistas -->
                    </ul>
                </li>
                <!-- @endcanany -->

                {{-- Ventas --}}
                <!-- @canany(['users.index','users.create']) -->
                <li class="nav-item has-treeview {{ request()->is('ventas*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('ventas*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-wallet"></i>
                        <p>
                            Ventas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- aca agregar vistas -->
                    </ul>
                </li>
                @endcanany
                @role('Admin')
                    <li class="nav-item has-treeview {{ request()->is('estacionamiento*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('estacionamiento*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-parking"></i>
                            <p>
                                Estacionamiento
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- Listado --}}
                            <li class="nav-item">
                                <a href="{{ route('estacionamiento.index') }}"
                                class="nav-link {{ request()->routeIs('estacionamiento.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listado</p>
                                </a>
                            </li>

                            {{-- Ingresar nuevo --}}
                            <li class="nav-item">
                                <a href="{{ route('estacionamiento.create') }}"
                                class="nav-link {{ request()->routeIs('estacionamiento.create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ingresar</p>
                                </a>
                            </li>


                            {{-- Historial --}}
                            <li class="nav-item">
                                <a href="{{ route('estacionamiento.history') }}"
                                class="nav-link {{ request()->routeIs('estacionamiento.history') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Historial</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endrole


                {{-- mantenimiento --}}
                <!-- @canany(['users.index','users.create']) -->
                <li class="nav-item has-treeview {{ request()->is('mantenimiento*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('mantenimiento*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                        <p>
                            Mant. vehiculos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- aca agregar vistas -->
                    </ul>
                </li>
                <!-- @endcanany -->

                {{-- Administración --}}
                @canany(['users.index','roles.index'])
                <li class="nav-item has-treeview {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p>
                    Administración
                    <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">

                    {{-- Configuración Interna --}}
                    <li class="nav-item has-treeview {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->routeIs('users.*') || request()->routeIs('roles.*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                        Configuración Interna
                        <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Usuarios (sólo índice) --}}
                        @can('users.index')
                        <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                        </li>
                        @endcan

                        {{-- Roles (sólo índice) --}}
                        @role('Admin')
                        <li class="nav-item">
                        <a href="{{ route('roles.index') }}"
                            class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i>
                            <p>Roles</p>
                        </a>
                        </li>
                        @endrole
                    </ul>
                    </li>

                </ul>
                </li>
                @endrole

                {{-- Mantenedores --}}
                @can('mantenedores.access')
                <li class="nav-item has-treeview
                    {{ request()->is('contratos*') || request()->is('reglas*') ||
                       request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ||
                       request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') ? 'menu-open' : '' }}">
                    
                    <a href="#" class="nav-link
                        {{ request()->is('contratos*') || request()->is('reglas*') ||
                           request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ||
                           request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>Mantenedores <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- Contratos --}}
                        <li class="nav-item has-treeview {{ request()->is('contratos*') || request()->is('reglas*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('contratos*') || request()->is('reglas*') ? 'active' : '' }}">
                                <i class="fas fa-file-signature nav-icon"></i>
                                <p>Contratos<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('contratos.index') }}" class="nav-link {{ request()->routeIs('contratos.index') ? 'active' : '' }}">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>Contratos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reglas.index') }}" class="nav-link {{ request()->routeIs('reglas.index') ? 'active' : '' }}">
                                        <i class="fas fa-gavel nav-icon"></i>
                                        <p>Reglas de contrato</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Autos --}}
                        <li class="nav-item has-treeview {{ request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('autos*') || request()->is('marca*') || request()->is('modelo*') || request()->is('dueños*') ? 'active' : '' }}">
                                <i class="fas fa-car nav-icon"></i>
                                <p>Autos<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('autos.index') }}" class="nav-link {{ request()->routeIs('autos.index') ? 'active' : '' }}">
                                        <i class="fas fa-car-side nav-icon"></i>
                                        <p>Nuevo auto</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('marca.index') }}" class="nav-link {{ request()->routeIs('marca.index') ? 'active' : '' }}">
                                        <i class="fas fa-flag nav-icon"></i>
                                        <p>Marcas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('modelo.index') }}" class="nav-link {{ request()->routeIs('modelo.index') ? 'active' : '' }}">
                                        <i class="fas fa-cubes nav-icon"></i>
                                        <p>Modelos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dueños.index') }}" class="nav-link {{ request()->routeIs('dueños.index') ? 'active' : '' }}">
                                        <i class="fas fa-user nav-icon"></i>
                                        <p>Dueños de vehiculos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Empresa --}}
                        <li class="nav-item has-treeview {{ request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('empresa*') || request()->is('locacion*') || request()->is('sucursales*') ? 'active' : '' }}">
                                <i class="fas fa-building nav-icon"></i>
                                <p>Empresa<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('empresa.index') }}" class="nav-link {{ request()->routeIs('empresa.index') ? 'active' : '' }}">
                                        <i class="fas fa-briefcase nav-icon"></i>
                                        <p>Datos de empresa</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('locacion.index') }}" class="nav-link {{ request()->routeIs('locacion.index') ? 'active' : '' }}">
                                        <i class="fas fa-map-marker-alt nav-icon"></i>
                                        <p>Locaciones</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('sucursales.index') }}" class="nav-link {{ request()->routeIs('sucursales.index') ? 'active' : '' }}">
                                        <i class="fas fa-store nav-icon"></i>
                                        <p>Sucursales</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('informacion_contacto.index') }}" class="nav-link {{ request()->routeIs('informacion_contacto.index') ? 'active' : '' }}">
                                        <i class="fas fa-address-book nav-icon"></i>
                                        <p>Información de Contacto</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
