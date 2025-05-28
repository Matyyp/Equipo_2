<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
  <!-- Brand Logo -->
  <a href="{{ url('/') }}" class="brand-link d-flex flex-column align-items-center justify-content-center py-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div 
      class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-2" 
      style="width: 64px; height: 64px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">

      <img src="{{ asset('central/logo-central.png') }}"
           alt="Logo" 
           style="max-width: 40px; max-height: 40px; filter: drop-shadow(0 0 4px rgba(30,60,114,0.3));">
    </div>
    <span class="brand-text font-weight-bold text-white text-center" style="font-size: 1.1rem; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
      MiTenancy Central
    </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}"
             class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
             style="color: #e9ecef; border-radius: 6px; margin: 4px 8px; transition: all 0.3s ease;">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Tenants -->
        <li class="nav-item {{ request()->routeIs('tenants.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('tenants.*') ? 'active' : '' }}"
             style="color: #e9ecef; border-radius: 6px; margin: 4px 8px; transition: all 0.3s ease;">
            <i class="nav-icon fas fa-building"></i>
            <p>Clientes</p>
            <i class="right fas fa-angle-left"></i>
          </a>
          <ul class="nav nav-treeview" style="background: rgba(0,0,0,0.1); border-radius: 0 0 6px 6px; margin: 0 8px; padding-left: 0;">
            <li class="nav-item">
              <a href="{{ route('tenants.index') }}" 
                 class="nav-link {{ request()->routeIs('tenants.index') ? 'active' : '' }}"
                 style="color: #e9ecef; padding-left: 52px; position: relative;">
                <i class="far fa-circle nav-icon" style="position: absolute; left: 28px;"></i>
                <p>Listado</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<style>
  .nav-link {
    padding: 0.5rem 1rem;
  }
  
  .nav-link.active {
    background: rgba(255,255,255,0.15) !important;
    color: #fff !important;
    font-weight: 500;
    box-shadow: 0 2px 12px rgba(0,0,0,0.15);
  }
  
  .nav-link:hover:not(.active) {
    background: rgba(255,255,255,0.08) !important;
    transform: translateX(2px);
  }
  
  .nav-treeview .nav-link {
    padding-top: 0.4rem;
    padding-bottom: 0.4rem;
  }
  
  .nav-treeview .nav-link.active {
    background: rgba(255,255,255,0.1) !important;
    border-left: 3px solid #fff;
    font-weight: 500;
  }
  
  .nav-treeview .nav-link:hover:not(.active) {
    background: rgba(255,255,255,0.05) !important;
  }
  
  .menu-open > .nav-link {
    background: rgba(255,255,255,0.1) !important;
  }
  
  .menu-open > .nav-link > i:not(.nav-icon) {
    transform: rotate(-90deg);
  }
</style>