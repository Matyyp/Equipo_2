@extends('tenant.layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Listado de Servicios')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-concierge-bell mr-2"></i>Servicios en la sucursal {{ $sucursal }} ubicada en {{ $direccion }}</div>
      <a href="{{ route('servicios.create', ['sucursal' => $sucursalId] ) }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Servicio Extra
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="servicios-table" class="table table-striped table-bordered align-middle mb-0 w-100">
          <thead class="thead-light">
            <tr>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Tipo de Servicio</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tipos as $key => $nombre)
              @php
                $service = $registrados[$key] ?? null;
                $isAvailable = $service && $service->status === 'available';
              @endphp

              @if ($key === 'extra' && !$isAvailable)
                @continue
              @endif

              <tr>
                <td>{{ $isAvailable ? $service->name : $nombre }}</td>
                <td>{{ $isAvailable ? '$' . number_format($service->price_net, 0, ',', '.') : '—' }}</td>
                <td>{{ $nombre }}</td>
                <td class="text-center">
                  @if ($isAvailable)
                    <a href="{{ route('servicios.edit', $service->id_service) }}"
                      class="btn btn-outline-warning btn-sm text-dark" title="Editar">
                      <i class="fas fa-pen"></i>
                    </a>
                    <button onclick="desactivarServicio({{ $service->id_service }}, '{{ $service->name }}')"
                            class="btn btn-sm btn-outline-danger text-dark" title="Desactivar">
                      <i class="fas fa-ban"></i>
                    </button>
                  @else
                    <button onclick="activarServicio('{{ $key }}', '{{ $nombre }}')"
                            class="btn btn-sm btn-outline-success" title="Activar">
                      <i class="fas fa-plus"></i> Activar
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
            <tr>
              <td>Lavado</td>
              <td>—</td>
              <td>Lavados</td>
              <td class="text-center">
                <a href="{{ route('lavados.show', $sucursalId) }}"
                  class="btn btn-outline-primary btn-sm text-dark" title="Ir a Lavado">
                  <i class="fas fa-soap"></i> Lavado de auto
                </a>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
      <a href="{{ route('sucursales.show', $sucursalId) }}"
         style="background-color: transparent; border: 1px solid #6c757d; color: #6c757d; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Sucursales
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  {{-- DataTables JS --}}
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      $('#servicios-table').DataTable({
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        paging: true,
        info: true,
        searching: true,
        ordering: true,
        order: [[0, 'asc']]
      });
    });
  </script>

  {{-- SweetAlert y lógica de activación/desactivación --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function activarServicio(tipo, nombre) {
      Swal.fire({
        title: 'Activar ' + nombre,
        input: 'number',
        inputLabel: 'Ingrese el precio del servicio',
        inputAttributes: { min: 0 },
        inputValidator: (value) => {
          if (!value || value <= 0) return 'Debe ingresar un precio válido';
        },
        showCancelButton: true,
        confirmButtonText: 'Guardar'
      }).then(result => {
        if (result.isConfirmed) {
          fetch("{{ route('servicios.store') }}", {
            method: "POST",
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              name: nombre,
              type_service: tipo,
              price_net: result.value,
              id_branch_office: {{ $sucursalId }}
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire('¡Guardado!', data.message, 'success')
                .then(() => location.reload());
            } else {
              Swal.fire('Error', data.message || 'No se pudo guardar.', 'error');
            }
          })
          .catch(() => {
            Swal.fire('Error', 'Ocurrió un problema al guardar el servicio.', 'error');
          });
        }
      });
    }

    function desactivarServicio(id, nombre) {
      Swal.fire({
        title: '¿Desactivar "' + nombre + '"?',
        text: 'Esto marcará el servicio como inactivo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/servicios/${id}/disable`, {
            method: 'PATCH',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              Swal.fire('¡Desactivado!', data.message, 'success')
                .then(() => location.reload());
            } else {
              Swal.fire('Error', data.message || 'No se pudo desactivar.', 'error');
            }
          })
          .catch(() => {
            Swal.fire('Error', 'No se pudo completar la acción.', 'error');
          });
        }
      });
    }

    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false
      });
    @endif
  </script>
@endpush
