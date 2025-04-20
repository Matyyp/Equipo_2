<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Listado de Servicios</h1>

    <a href="{{ route('servicios.create') }}?sucursal_id={{ $sucursalId }}" class="btn btn-success">
        Ingresar Servicio
    </a>



    @if ($data->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio Neto</th>
                        <th>Tipo de Servicio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $service)
                        <tr>
                            <td>{{ $service->id_service }}</td>
                            <td>{{ $service->name }}</td>
                            <td>${{ number_format($service->price_net, 0, ',', '.') }}</td>
                            <td>
                                @if ($service->type_service=='parking')
                                    Estacionamiento
                                @elseif ($service->type_service=='car_wash')
                                    Lavado de Autos
                                @elseif ($service->type_service=='rent')
                                    Arriendo
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('servicios.edit', $service->id_service) }}" class="btn btn-warning btn-sm mb-1">
                                    Editar
                                </a>

                                <form action="{{ route('servicios.destroy', $service->id_service) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">No hay servicios registrados.</div>
    @endif
</div>

{{-- SweetAlert para eliminar y mensajes flash --}}
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Eliminar servicio?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

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
</body>
</html>

