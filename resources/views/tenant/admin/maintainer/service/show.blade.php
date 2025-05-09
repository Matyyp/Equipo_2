@extends('tenant.layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Listado de Servicios')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Servicios Registrados</h3>
        <br>
        <h4 class="card-title">En la sucursal {{ $sucursal }} ubicada en {{ $direccion }}</h4>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Precio Neto</th>
                        <th>Tipo de Servicio</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tipos as $key => $nombre)
                        @php
                            $service = $registrados[$key] ?? null;
                        @endphp
                        <tr>
                            <td>{{ $service->name ?? $nombre }}</td>
                            <td>
                                {{ $service ? '$' . number_format($service->price_net, 0, ',', '.') : '—' }}
                            </td>
                            <td>{{ $nombre }}</td>
                            <td class="text-right">
                                @if ($service)
                                    <a href="{{ route('servicios.edit', $service->id_service) }}" class="btn btn-warning btn-sm mb-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                @else
                                    <button class="btn btn-success btn-sm mb-1" onclick="activarServicio('{{ $key }}', '{{ $nombre }}')">
                                        <i class="fas fa-plus"></i> Activar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Sucursales
        </a>
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
                        'Accept': 'application/json' // ← IMPORTANTE para evitar error
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
