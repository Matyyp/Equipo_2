<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Logo -->
  <a href="{{ url('/') }}" class="brand-link">
    <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}"
         alt="Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
    <span class="brand-text font-weight-light">MiTenancy Central</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

        {{-- Home / Dashboard --}}
        <li class="nav-item">
          <a href="{{ route('dashboard') }}"
             class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-home"></i>
            <p>Home</p>
          </a>
        </li>

        {{-- Clientes / Tenants --}}
        <li class="nav-item">
          <a href="{{ route('tenants.index') }}"
             class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-building"></i>
            <p>Clientes</p>
          </a>
        </li>
        
        {{-- Ajustes --}}
        <li class="nav-item">
          <a href="{{ route('settings.edit') }}"
             class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>Ajustes</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
