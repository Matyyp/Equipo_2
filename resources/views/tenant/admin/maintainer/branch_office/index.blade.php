@extends('tenant.layouts.admin')

@section('title', 'Sucursales')
@section('page_title', 'Listado de Sucursales')
@push('styles')
<style>
     table.dataTable td,
    table.dataTable th {
      border: none !important;
    }

    table.dataTable tbody tr {
      border: none !important;
    }

    table.dataTable {
      border-top: 2px solid #dee2e6;
      border-bottom: 2px solid #dee2e6;
    }

    .dataTables_paginate .pagination .page-item.active a.page-link {
      background-color: #17a2b8 !important; 
      color:rgb(255, 255, 255) !important;
      border-color: #17a2b8 !important; 
    }

  
    .dataTables_paginate .pagination .page-item .page-link {
      background-color: #eeeeee;
      color: #17a2b8 !important;
      border-color: #eeeeee;
    }
    
    .btn-outline-info.text-info:hover,
    .btn-outline-info.text-info:focus {
      color: #fff !important;
    }
    
    
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center w-100">
        <div><i class="fas fa-store-alt mr-2"></i>Sucursales Registradas</div>

        @if ($verificacion)
          <a href="{{ route('sucursales.create') }}"
            style="background-color: transparent; border: 1px solid white; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-plus"></i> Nuevo
          </a>
        @else
          <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <span class="mr-2">Debe completar los datos de la empresa.</span>
            <a href="{{ route('empresa.index') }}" class="btn btn-sm btn-success">Ingresar datos empresa</a>
          </div>
        @endif
      </div>
    </div>


    <div class="card-body">
      <div class="table-responsive">
        <table id="branches-table" class="table table-striped w-100">
          <thead class="thead-light">
            <tr>
              <th>Sucursal</th>
              <th>Información</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($data as $branch)
              <tr>
                <td>{{ $branch['name_branch_offices'] }}</td>
                <td>
                  <div><strong>Horario:</strong> {{ $branch['schedule'] }}</div>
                  <div><strong>Dirección:</strong> {{ $branch['street'] }}</div>
                  <div><strong>Región:</strong> {{ $branch['region'] }}</div>
                  <div><strong>Comuna:</strong> {{ $branch['commune'] }}</div>
                  <div><strong>Negocio:</strong> {{ $branch['business'] }}</div>
                </td>
                <td class="text-center">
                  <a href="{{ route('sucursales.show', $branch['id']) }}"
                    class="btn btn-outline-secondary btn-sm text-dark" title="Configuración">
                    <i class="fas fa-cog"></i>
                  </a>
                </td>
              </tr>
            @empty
              {{-- Deja vacío para que DataTables maneje el mensaje --}}
            @endforelse
          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function() {
    $('#branches-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar sucursal?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then(result => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
