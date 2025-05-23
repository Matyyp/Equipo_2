@extends('tenant.layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Listado de Servicios')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">
        <i class="fas fa-concierge-bell me-2"></i>Servicios en la sucursal {{ $sucursal }} ubicada en {{ $direccion }}
      </h5>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0 w-100">
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
              <tr>
                <td>{{ $isAvailable ? $service->name : $nombre }}</td>
                <td>{{ $isAvailable ? '$' . number_format($service->price_net, 0, ',', '.') : '—' }}</td>
                <td>{{ $nombre }}</td>
                <td class="text-center">
                  @if ($isAvailable)
                    <a href="{{ route('servicios.edit', $service->id_service) }}" class="btn btn-warning btn-sm me-1">
                      <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <button onclick="desactivarServicio({{ $service->id_service }}, '{{ $service->name }}')" class="btn btn-danger btn-sm">
                      <i class="fas fa-ban me-1"></i> Desactivar
                    </button>
                  @else
                    <button class="btn btn-success btn-sm" onclick="activarServicio('{{ $key }}', '{{ $nombre }}')">
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
      <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Volver a Sucursales
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function activarServicio(tipo, nombre) {
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
