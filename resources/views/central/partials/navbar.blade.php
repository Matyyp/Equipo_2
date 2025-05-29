<nav class="main-header navbar navbar-expand navbar-dark" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-bottom: 1px solid rgba(255,255,255,0.1) !important;">
  <div class="container-fluid">
    <!-- Botón para colapsar/expandir el sidebar -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars" style="color: rgba(255,255,255,0.8);"></i>
        </a>
      </li>
    </ul>

    <!-- Lado derecho de la navbar -->
    <ul class="navbar-nav ms-auto px-2">

      @guest
        <li class="nav-item">
          <a class="nav-link btn btn-outline-light btn-sm mx-1" href="{{ route('login') }}" style="border-radius: 20px;">
            <i class="fas fa-sign-in-alt me-1"></i> Login
          </a>
        </li>
        @if(Route::has('register'))
          <li class="nav-item">
            <a class="nav-link btn btn-light btn-sm mx-1" href="{{ route('register') }}" style="border-radius: 20px;">
              <i class="fas fa-user-plus me-1"></i> Registro
            </a>
          </li>
        @endif
      @else
        <li class="nav-item dropdown px-5">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
          </a>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="fas fa-sign-out-alt text-danger me-2"></i> Cerrar sesión
              </button>
            </form>
          </div>
        </li>
      @endguest

    </ul>
  </div>
</nav>

<!-- Asegúrate de incluir estos scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
  .dropdown-item {
    padding: 0.5rem 1.25rem;
    transition: all 0.2s;
    border: none;
    background: none;
    text-align: left;
    width: 100%;
  }
  
  .dropdown-item:hover {
    background-color: rgba(30, 60, 114, 0.05) !important;
    padding-left: 1.5rem;
  }
  
  .dropdown-menu {
    min-width: 220px;
    border: 1px solid rgba(0,0,0,0.05) !important;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
  }
  
  .nav-link.btn-outline-light:hover {
    background: rgba(255,255,255,0.15);
  }
  
  .nav-link.btn-light {
    color: #1e3c72;
    font-weight: 500;
  }
  
  /* Fix for form button in dropdown */
  .dropdown-menu form {
    margin: 0;
  }
  
  .dropdown-divider {
    border-color: rgba(0,0,0,0.05);
    margin: 0.25rem 0;
  }
</style>