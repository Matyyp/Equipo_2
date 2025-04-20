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

                {{-- Usuarios: visible a Admin o Empleado --}}
                @role('Admin|Empleado')
                <li class="nav-item has-treeview {{ request()->is('users*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        {{-- Listado de usuarios (Admin y Empleado) --}}
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                               class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>

                        {{-- Crear nuevo usuario (solo Admin) --}}
                        @role('Admin')
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}"
                               class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo usuario</p>
                            </a>
                        </li>
                        @endrole

                    </ul>
                </li>
                @endrole

            </ul>
        </nav>
    </div>
</aside>
