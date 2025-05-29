@extends('tenant.layouts.admin')

@section('title', 'Lavado de Autos')
@section('page_title', 'Tipos de Lavado por Sucursal')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
<style>
    .btn-outline-info.text-info:hover,
    .btn-outline-info.text-info:focus {
      color: #fff !important;
    }
    
    
</style>
@endpush
@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">
        <i class="fas fa-soap me-2"></i> Lavado de autos disponibles en la sucursal
      </h5>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0 w-100">
          <thead class="thead-light">
            <tr>
              <th>Nombre</th>
              <th>Precio</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($lavados as $nombre)
              @php
                $service = $registrados[$nombre] ?? null;
                $isActivo = $service !== null;
              @endphp
              <tr>
                <td>{{ $nombre }}</td>
                <td>{{ $isActivo ? '$' . number_format($service->price_net, 0, ',', '.') : '—' }}</td>
                <td class="text-center">
                  @if ($isActivo)
                    <a href="{{ route('lavados.edit', $service->id_service) }}" class="btn btn-outline-info btn-sm text-info me-1">
                      <i class="fas fa-edit me-1"></i>
                    </a>
                    <button onclick="desactivarLavado({{ $service->id_service }}, '{{ $service->name }}')" class="btn btn-outline-info btn-sm text-info">
                      <i class="fas fa-ban me-1"></i>
                    </button>
                  @else
                    <button class="btn btn-outline-success btn-sm" onclick="activarLavado('{{ $nombre }}')">
                      <i class="fas fa-plus me-1"></i> Activar
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
      <a href="{{ route('servicios.show' , $branchOfficeId) }}" class="btn btn-secondary">
        Cancelar
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function activarLavado(nombre) {
    Swal.fire({
      title: 'Activar ' + nombre,
      input: 'number',
      inputLabel: 'Ingrese el precio neto',
      inputAttributes: { min: 0 },
      inputValidator: (value) => {
        if (!value || value <= 0) return 'Debe ingresar un precio válido';
      },
      showCancelButton: true,
      confirmButtonText: 'Guardar'
    }).then(result => {
      if (result.isConfirmed) {
        fetch("{{ route('lavados.store') }}", {
          method: "POST",
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            name: nombre,
            type_service: 'car_wash',
            price_net: result.value,
            id_branch_office: {{ $branchOfficeId }}
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
          Swal.fire('Error', 'Ocurrió un problema al guardar el lavado.', 'error');
        });
      }
    });
  }

  function desactivarLavado(id, nombre) {
    Swal.fire({
      title: '¿Desactivar "' + nombre + '"?',
      text: 'Esto marcará el lavado como inactivo.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, desactivar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch(`/lavados/${id}/disable`, {
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
