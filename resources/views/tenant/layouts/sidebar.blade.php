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
                <!-- @endcanany -->

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
                <!-- @canany(['users.index','users.create','roles.index','roles.create']) -->
                <li class="nav-item has-treeview {{ request()->is('administracion*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('administracion*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Administración
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Configuración Interna --}}
                        <li class="nav-item has-treeview {{ request()->is('administracion/configuracion-interna*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('administracion/configuracion-interna*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Configuración Interna
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- Usuarios --}}
                                @canany(['users.index','users.create'])
                                <li class="nav-item has-treeview {{ request()->is('administracion/configuracion-interna/users*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('administracion/configuracion-interna/users*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Usuarios
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('users.index')
                                        <li class="nav-item">
                                            <a href="{{ route('users.index') }}"
                                            class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Listado</p>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('users.create')
                                        <li class="nav-item">
                                            <a href="{{ route('users.create') }}"
                                            class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Nuevo usuario</p>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcanany

                                {{-- Roles --}}
                                @role('Admin')
                                <li class="nav-item has-treeview {{ request()->is('administracion/configuracion-interna/roles*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('administracion/configuracion-interna/roles*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Roles
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('roles.index') }}"
                                            class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Listado</p>
                                            </a>
                                        </li>
                                        
                                        <li class="nav-item">
                                            <a href="{{ route('roles.create') }}"
                                            class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Nuevo rol</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endrole
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- @endcanany -->
            </ul>
        </nav>
    </div>
</aside>
