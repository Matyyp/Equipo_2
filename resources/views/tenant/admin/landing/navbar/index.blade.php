@extends('tenant.layouts.admin')

@section('title', 'Gestionar Navbar')
@section('page_title', 'Gestionar Navbar')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-bars mr-2"></i>Navbar</div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="navbar-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Reservas</th>
              <th>Horario</th>
              <th>Correo</th>
              <th>Dirección</th>
              <th>Servicios</th>
              <th>Quienes Somos</th>
              <th>Contáctanos</th>
              <th>Colores</th>
              <th>Botones</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $navbar->reservations }}</td>
              <td>{{ $navbar->schedule }}</td>
              <td>{{ $navbar->email }}</td>
              <td>{{ $navbar->address }}</td>
              <td>{{ $navbar->services }}</td>
              <td>{{ $navbar->about_us }}</td>
              <td>{{ $navbar->contact_us }}</td>
              <td>
                <div class="d-flex flex-column gap-1 small">
                  @foreach([
                    'Fondo 1' => $navbar->background_color_1,
                    'Fondo 2' => $navbar->background_color_2,
                    'Botón 1' => $navbar->button_color_1,
                    'Botón 2' => $navbar->button_color_2,
                    'Texto 1' => $navbar->text_color_1,
                    'Texto 2' => $navbar->text_color_2
                  ] as $label => $color)
                    <div class="d-flex align-items-center gap-2">
                      <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color }};border-radius:50%;border:1px solid #ccc;"></span>
                      {{ $label }}
                    </div>
                  @endforeach
                </div>
              </td>
              <td>
                <div class="d-flex flex-column small">
                  <div><strong>Botón 1:</strong> {{ $navbar->button_1 }}</div>
                  <div><strong>Botón 2:</strong> {{ $navbar->button_2 }}</div>
                </div>
              </td>
              <td>
                <a href="{{ route('landing.navbar.edit', $navbar) }}" class="btn btn-outline-secondary btn-sm text-dark me-1" title="Editar">
                    <i class="fas fa-pen"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- jQuery + DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  $('#navbar-table').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true
  });
});
</script>
@endpush
