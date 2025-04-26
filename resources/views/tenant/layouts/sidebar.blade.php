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

                {{-- Usuarios: aparecerá si puede ver la lista o crear usuarios --}}
                @canany(['users.index','users.create'])
                <li class="nav-item has-treeview {{ request()->is('users*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Listado --}}
                        @can('users.index')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                               class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        @endcan

                        {{-- Nuevo usuario --}}
                        @can('users.create')
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}"
                               class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo usuario</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Roles: aparecerá si puede listar o crear roles --}}
                @canany(['roles.index','roles.create'])
                <li class="nav-item has-treeview {{ request()->is('roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>
                            Roles
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Listado --}}
                        @can('roles.index')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                               class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        @endcan

                        {{-- Nuevo rol --}}
                        @can('roles.create')
                        <li class="nav-item">
                            <a href="{{ route('roles.create') }}"
                               class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo rol</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Permisos: aparecerá si puede listar o crear permisos --}}
                @canany(['permissions.index','permissions.create'])
                <li class="nav-item has-treeview {{ request()->is('permissions*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('permissions*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key"></i>
                        <p>
                            Permisos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Listado --}}
                        @can('permissions.index')
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}"
                               class="nav-link {{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        @endcan

                        {{-- Nuevo permiso --}}
                        @can('permissions.create')
                        <li class="nav-item">
                            <a href="{{ route('permissions.create') }}"
                               class="nav-link {{ request()->routeIs('permissions.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo permiso</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @role('Admin')
                <li class="nav-item has-treeview {{ request()->is('roles*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Roles <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        {{-- Crear nuevo rol (solo Admin) --}}
                        @role('Admin')
                        <li class="nav-item">
                            <a href="{{ route('roles.create') }}"
                               class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Rol</p>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
                @endrole

                @can('mantenedores.access')
                <li class="nav-item has-treeview
                    {{ request()->is('empresa*') || request()->is('locacion*') || request()->is('accesorio*') || request()->is('informacion_contacto*')
                        || request()->is('modelo*') || request()->is('marca*') || request()->is('dueños*') || request()->is('reglas*')
                        || request()->is('autos*') || request()->is('sucursales*') || request()->is('contratos*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link
                        {{ request()->is('empresa*') || request()->is('locacion*') || request()->is('accesorio*') || request()->is('informacion_contacto*')
                            || request()->is('modelo*') || request()->is('marca*') || request()->is('dueños*') || request()->is('reglas*')
                            || request()->is('autos*') || request()->is('sucursales*') || request()->is('contratos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Mantenedores <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        {{-- Empresa --}}
                        <li class="nav-item">
                            <a href="{{ route('empresa.index') }}" class="nav-link {{ request()->routeIs('empresa.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Empresa</p>
                            </a>
                        </li>

                        {{-- Locación --}}
                        <li class="nav-item">
                            <a href="{{ route('locacion.index') }}" class="nav-link {{ request()->routeIs('locacion.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Locaciones</p>
                            </a>
                        </li>

                        <!-- {{-- Accesorios --}}
                        <li class="nav-item">
                            <a href="{{ route('accesorio.index') }}" class="nav-link {{ request()->routeIs('accesorio.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Accesorios</p>
                            </a>
                        </li> -->

                        {{-- Información de Contacto --}}
                        <li class="nav-item">
                            <a href="{{ route('informacion_contacto.index') }}" class="nav-link {{ request()->routeIs('informacion_contacto.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Información Contacto</p>
                            </a>
                        </li>

                        {{-- Modelos --}}
                        <li class="nav-item">
                            <a href="{{ route('modelo.index') }}" class="nav-link {{ request()->routeIs('modelo.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Modelos</p>
                            </a>
                        </li>

                        {{-- Marcas --}}
                        <li class="nav-item">
                            <a href="{{ route('marca.index') }}" class="nav-link {{ request()->routeIs('marca.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Marcas</p>
                            </a>
                        </li>

                        {{-- Dueños --}}
                        <li class="nav-item">
                            <a href="{{ route('dueños.index') }}" class="nav-link {{ request()->routeIs('dueños.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dueños</p>
                            </a>
                        </li>

                        {{-- Reglas --}}
                        <li class="nav-item">
                            <a href="{{ route('reglas.index') }}" class="nav-link {{ request()->routeIs('reglas.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reglas</p>
                            </a>
                        </li>

                        {{-- Autos --}}
                        <li class="nav-item">
                            <a href="{{ route('autos.index') }}" class="nav-link {{ request()->routeIs('autos.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Autos</p>
                            </a>
                        </li>

                        {{-- Sucursales --}}
                        <li class="nav-item">
                            <a href="{{ route('sucursales.index') }}" class="nav-link {{ request()->routeIs('sucursales.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sucursales</p>
                            </a>
                        </li>

                        {{-- Contratos --}}
                        <li class="nav-item">
                            <a href="{{ route('contratos.index') }}" class="nav-link {{ request()->routeIs('contratos.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contratos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
