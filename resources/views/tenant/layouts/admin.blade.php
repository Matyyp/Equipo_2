<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        {{-- 2. (Opcional) Links rápidos o búsqueda al centro --}}
        <ul class="navbar-nav mx-auto">
            {{-- ... --}}
        </ul>

        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    <i class="fas fa-user-circle fa-lg ml-1"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    {{-- Perfil --}}
                    <li class="dropdown-item">
                        <a href="{{ route('profile.edit') }}" class="d-block">
                            <i class="fas fa-user-cog mr-2"></i> {{ __('Profile') }}
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>

                    <li class="dropdown-item">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="d-block text-danger">
                                <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Log Out') }}
                            </a>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    @include('tenant.layouts.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    {{-- Footer opcional --}}
    <footer class="main-footer text-sm">
        <strong>&copy; {{ now()->year }} — Tu Empresa</strong>
    </footer>
</div>

{{-- REQUIRED JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
