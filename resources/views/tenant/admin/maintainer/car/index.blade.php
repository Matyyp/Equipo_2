@extends('tenant.layouts.admin')

@section('title', 'Listado de Autos')
@section('page_title', 'Listado de Autos')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-car mr-2"></i>Autos registrados</div>
      <a href="{{ route('autos.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="cars-table" class="table table-striped table-bordered w-100">
          <thead class="thead-light">
            <tr>
              <th>Patente</th>
              <th>Marca</th>
              <th>Modelo</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($car as $item)
              <tr>
                <td>{{ $item['patent'] }}</td>
                <td>{{ $item['brand'] }}</td>
                <td>{{ $item['model'] }}</td>
                <td class="text-center">
                  <a href="{{ route('autos.edit', ['auto' => $item['id']]) }}"
                    class="btn btn-outline-secondary btn-sm text-dark" title="Editar">
                    <i class="fas fa-pen"></i>
                  </a>
                </td>
              </tr>
            @empty
              {{-- Dejar vacío para que DataTables muestre su propio mensaje --}}
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
  $(document).ready(function () {
    $('#cars-table').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
      }
    });

    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar este auto?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
