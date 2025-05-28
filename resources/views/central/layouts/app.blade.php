<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Central')</title>

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Estilos personalizados -->
  <style>
    .main-sidebar, .brand-link, .nav-sidebar .nav-link {
      transition: all 0.3s ease;
    }

    .sidebar-collapse .brand-text,
    .sidebar-collapse .nav-sidebar .nav-link p,
    .sidebar-collapse .nav-sidebar .nav-link .right-badge {
      display: none;
    }

    .sidebar-collapse .nav-sidebar .nav-link {
      justify-content: center;
    }

    .sidebar-collapse .nav-sidebar .nav-treeview {
      display: none !important;
    }

    .brand-link {
      padding: 1.1rem 0.8rem;
    }

    .nav-link.active {
      border-left: 3px solid #007bff;
    }

    /* Dropdown mejorado */
    .dropdown-menu {
      border: 1px solid rgba(0,0,0,.1);
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,.1);
    }

    .dropdown-item {
      padding: 0.5rem 1.5rem;
      transition: all 0.2s;
    }

    .dropdown-item:hover {
      background-color: #f8f9fa;
      padding-left: 1.75rem;
    }

    /* Logout button styles */
    .dropdown-menu form button {
      width: 100%;
      text-align: left;
      padding: 0.5rem 1.5rem;
      background: none;
      border: none;
      color: #212529;
    }

    .dropdown-menu form button:hover {
      background-color: #f8f9fa;
      color: #dc3545;
    }

    .dropdown-menu form button i {
      margin-right: 0.5rem;
      width: 20px;
      text-align: center;
    }
  </style>

  @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    @include('central.partials.navbar')

    <!-- Sidebar -->
    @include('central.partials.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Content Header -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="col-sm-6">
              @yield('breadcrumb')
            </div>
          </div>
        </div>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </section>
    </div>

    <!-- Footer -->
    @include('central.partials.footer')
  </div>

  <!-- Scripts (IMPORTANTE: Orden correcto y versiones compatibles) -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

  <script>
    $(function () {
      // Inicializar tooltips
      $('[data-toggle="tooltip"]').tooltip();

      // Inicializar dropdowns (necesario para AdminLTE 3)
      $('.dropdown-toggle').dropdown();

      function setupSidebarTooltips() {
        if ($('body').hasClass('sidebar-collapse')) {
          $('.nav-sidebar .nav-link:not(.has-treeview)').each(function() {
            const text = $(this).find('p').text();
            $(this).attr('data-toggle', 'tooltip')
                   .attr('title', text)
                   .attr('data-placement', 'right');
          });
        } else {
          $('.nav-sidebar .nav-link').removeAttr('data-toggle title data-placement');
          $('.nav-sidebar .nav-link').tooltip('dispose');
        }
      }

      // Inicializar tooltips al cargar
      setupSidebarTooltips();

      // Tooltips al colapsar o expandir el sidebar
      $('body').on('collapsed.lte.pushmenu', setupSidebarTooltips);
      $('body').on('shown.lte.pushmenu', setupSidebarTooltips);

      // Guardar estado del sidebar
      if (localStorage.getItem('sidebarCollapsed') === 'true') {
        $('body').addClass('sidebar-collapse');
      }

      $('body').on('collapsed.lte.pushmenu', function () {
        localStorage.setItem('sidebarCollapsed', true);
      });

      $('body').on('shown.lte.pushmenu', function () {
        localStorage.setItem('sidebarCollapsed', false);
      });
    });
  </script>

  @stack('scripts')
</body>
</html>