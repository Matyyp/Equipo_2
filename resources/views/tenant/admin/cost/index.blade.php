@extends('tenant.layouts.admin')

@section('title', 'Ingresos por Servicios Básicos')
@section('page_title', 'Ingresos por Servicios Básicos')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<style>
  #sidebar-overlay {
      pointer-events: none !important;
      background: transparent !important;
  }
  table.dataTable td,
    table.dataTable th {
      border: none !important;
    }

    table.dataTable tbody tr {
      border: none !important;
    }

    /* Agregar solo el borde superior e inferior a la tabla completa */
    table.dataTable {
      border-top: 2px solid #dee2e6;
      border-bottom: 2px solid #dee2e6;
    }

    .dataTables_paginate .pagination .page-item.active .page-link {
      background-color: #17a2b8 !important; 
      border-color: #17a2b8 !important; 
    }
    .dataTables_paginate .pagination .page-item.disabled .page-link {
  background-color: #eeeeee;
  color: #17a2b8 !important;
  border-color: #eeeeee;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center flex-wrap w-100">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <i class="fas fa-money-bill-wave mr-2"></i>
          <span class="fw-semibold h6 mb-0">Ingresos por Servicios Básicos</span>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-4">
        <h5 class="mb-3"><i class="text-primary"></i> Filtros</h5>
        <div class="row">
          @role('SuperAdmin')
          <div class="col-md-3 mb-2">
            <label for="filter-sucursal" class="form-label fw-bold">Sucursal</label>
            <select id="filter-sucursal" class="form-control" required>
              <option value="">Ninguna sucursal seleccionada</option>
              @foreach($sucursales as $sucursal)
                <option value="{{ $sucursal->id_branch }}">{{ $sucursal->name_branch_offices }}</option>
              @endforeach
            </select>
          </div>
          <div id="filtros-fecha" class="col-md-9 row" style="display:none;">
            <div class="col-md-4 mb-2">
              <label for="filter-dia" class="form-label fw-bold">Día</label>
              <input type="date" id="filter-dia" class="form-control" disabled>
            </div>
            <div class="col-md-4 mb-2">
              <label for="filter-mes" class="form-label fw-bold">Mes</label>
              <select id="filter-mes" class="form-control" disabled>
                <option value="">Selecciona mes</option>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label for="filter-anio" class="form-label fw-bold">Año</label>
              <select id="filter-anio" class="form-control" disabled>
                <option value="">Selecciona año</option>
                @for($y = date('Y'); $y >= 2000; $y--)
                  <option value="{{ $y }}">{{ $y }}</option>
                @endfor
              </select>
            </div>
          </div>
          @else
            <input type="hidden" id="filter-sucursal" value="{{ auth()->user()->branch_office_id }}">
            <div id="filtros-fecha" class="col-md-9 row">
              <div class="col-md-4 mb-2">
                <label for="filter-dia" class="form-label fw-bold">Día</label>
                <input type="date" id="filter-dia" class="form-control">
              </div>
              <div class="col-md-4 mb-2">
                <label for="filter-mes" class="form-label fw-bold">Mes</label>
                <select id="filter-mes" class="form-control">
                  <option value="">Selecciona mes</option>
                  <option value="1">Enero</option>
                  <option value="2">Febrero</option>
                  <option value="3">Marzo</option>
                  <option value="4">Abril</option>
                  <option value="5">Mayo</option>
                  <option value="6">Junio</option>
                  <option value="7">Julio</option>
                  <option value="8">Agosto</option>
                  <option value="9">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
              <div class="col-md-4 mb-2">
                <label for="filter-anio" class="form-label fw-bold">Año</label>
                <select id="filter-anio" class="form-control">
                  <option value="">Selecciona año</option>
                  @for($y = date('Y'); $y >= 2000; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                  @endfor
                </select>
              </div>
            </div>
          @endrole
        </div>
      </div>
      <div class="table-responsive">
        <table id="ingresos-table" class="table table-striped nowrap w-100">
          <thead>
            <tr>
              <th>Fuente</th>
              <th>Total</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>

<script>
  function limpiarOtrosFiltros(except) {
    if (except !== 'dia') $('#filter-dia').val('');
    if (except !== 'mes') $('#filter-mes').val('');
    if (except !== 'anio') $('#filter-anio').val('');
  }
  let tabla;
  $(function() {
    tabla = $('#ingresos-table').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ordering: false,
      ajax: {
        url: '{{ route("cost_basic_service.ingresos.data") }}',
        data: function(d) {
          d.sucursal_id = $('#filter-sucursal').val();
          d.filtro_dia = $('#filter-dia').val();
          d.filtro_mes = $('#filter-mes').val();
          d.filtro_anio = $('#filter-anio').val();
        },
        dataSrc: function(json) {
          if ($('#filter-sucursal').length && $('#filter-sucursal').val() === '') {
            return [];
          }
          return json.data;
        },
        error: function(xhr, error, thrown) {
          console.error('AJAX Error:', xhr.status, thrown);
          alert(`Error cargando datos: ${xhr.status} – ${thrown}`);
        }
      },
      columns: [
        { 
          data: 'fuente', name: 'fuente',
          render: function(data, type, row) {
            if (row.tipo === 'operacion') {
              return '<strong>' + data + '</strong>';
            }
            return data;
          }
        },
        { 
          data: 'total', name: 'total',
          render: function(data, type, row) {
            if (row.tipo === 'ingreso') {
              return `<span class="text-success"><i class="fas fa-plus"></i> $${parseFloat(data || 0).toLocaleString('es-CL')}</span>`;
            } else if (row.tipo === 'costo') {
              return `<span class="text-danger"><i class="fas fa-minus"></i> $${parseFloat(data || 0).toLocaleString('es-CL')}</span>`;
            } else if (row.tipo === 'operacion') {
              let clase = data >= 0 ? "text-success" : "text-danger";
              let icon = data >= 0 ? '<i class="fas fa-plus"></i>' : '<i class="fas fa-minus"></i>';
              return `<span class="${clase}"><strong>${icon} $${Math.abs(parseFloat(data || 0)).toLocaleString('es-CL')}</strong></span>`;
            } else {
              return data;
            }
          }
        }
      ],
      paging: false,
      searching: false,
      info: false,
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    tabla.clear().draw();

    $('#filter-dia').on('change', function() {
      if ($(this).val()) {
        limpiarOtrosFiltros('dia');
      }
      tabla.ajax.reload();
    });
    $('#filter-mes').on('change', function() {
      if ($(this).val()) {
        limpiarOtrosFiltros('mes');
      }
      tabla.ajax.reload();
    });
    $('#filter-anio').on('change', function() {
      if ($(this).val()) {
        limpiarOtrosFiltros('anio');
      }
      tabla.ajax.reload();
    });

    @role('SuperAdmin')
    $('#filter-sucursal').on('change', function() {
      let sucursalId = $(this).val();
      if (sucursalId === '') {
        $('#filtros-fecha').hide();
        $('#filter-dia, #filter-mes, #filter-anio').prop('disabled', true).val('');
        tabla.clear().draw();
      } else {
        $('#filtros-fecha').show();
        $('#filter-dia, #filter-mes, #filter-anio').prop('disabled', false);
        tabla.ajax.reload();
      }
    });
    if ($('#filter-sucursal').val() === '') {
      $('#filtros-fecha').hide();
      $('#filter-dia, #filter-mes, #filter-anio').prop('disabled', true).val('');
    } else {
      $('#filtros-fecha').show();
      $('#filter-dia, #filter-mes, #filter-anio').prop('disabled', false);
    }
    @endrole
  });
</script>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById('sidebar-overlay');
    if (overlay) {
      overlay.style.display = 'none';
      overlay.remove();
    }
    $('.user-menu > a').on('click', function (e) {
      e.preventDefault();
      $(this).siblings('.dropdown-menu').toggle();
    });
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.user-menu').length) {
        $('.user-menu .dropdown-menu').hide();
      }
    });
  });
</script>
@endpush