<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <!-- Toggle sidebar si lo tienes -->
    <button class="btn btn-outline-secondary me-2" type="button" id="sidebarToggle">
      <i class="fas fa-bars"></i>
    </button>
    
    <!-- Right side -->
    <ul class="navbar-nav ms-auto">

      @guest
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">Login</a>
        </li>
        @if(Route::has('register'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Register</a>
          </li>
        @endif

      @else
        <li class="nav-item dropdown">
          <a 
            class="nav-link dropdown-toggle" 
            href="#" 
            id="userDropdown" 
            role="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
          >
            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="fas fa-user-cog me-1"></i> Perfil
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                  <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                </button>
              </form>
            </li>
          </ul>
        </li>
      @endguest

    </ul>
  </div>
</nav>
