{{-- resources/views/tenant/admin/parking/parking_index.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Listado de Ingresos')
@section('page_title', 'Listado de Ingresos')

@push('styles')
  <!-- DataTables Bootstrap4 CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"
  />
@endpush

@section('content')
<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-table me-2"></i>Listado de Ingresos
    </div>
    <div class="card-body">
      <table id="parking-table" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Patente</th>
            <th>Auto</th>
            <th>Inicio</th>
            <th>Término</th>
            <th>Días</th>
            <th>Precio Servicio</th>
            <th>Total</th>
            <th>Acciones</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  $('#parking-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '{{ route("estacionamiento.index") }}',  
      dataSrc: 'data'                                
    },
    columns: [
      { data: 'owner_name' },
      { data: 'patent' },
      { data: 'brand_model' },
      { data: 'start_date' },
      { data: 'end_date' },
      { data: 'days' },
      { data: 'service_price' },
      { data: 'total_value' },
      { 
        data: 'id_parking_register',
        orderable: false,
        searchable: false,
        render: function(id) {
          return `
            <a href="/contrato/${id}/print" target="_blank" 
               class="btn btn-sm btn-outline-primary me-1" 
               title="Contrato">
              <i class="fas fa-file-contract"></i>
            </a>
            <a href="/estacionamiento/${id}/ticket" 
               class="btn btn-sm btn-outline-secondary me-1" 
               title="Ticket">
              <i class="fas fa-ticket-alt"></i>
            </a>
            <a href="/estacionamiento/${id}/edit" 
               class="btn btn-sm btn-outline-info me-1" 
               title="Editar">
              <i class="fas fa-edit"></i>
            </a>
            <form action="/estacionamiento/${id}/checkout" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-success" title="Check-Out">
                <i class="fas fa-sign-out-alt"></i>
              </button>
            </form>
          `;
        }
      }
    ],
    order: [[3, 'desc']], 
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    }
  });
});
</script>
@endpush