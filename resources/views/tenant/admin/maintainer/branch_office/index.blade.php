<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Sucursales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Listado de Sucursales</h1>

    <a href="{{ route('sucursales.create') }}" class="btn btn-success mb-3">Nueva Sucursal</a>

    @if ($data->count())
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Horario</th>
                    <th>Calle</th>
                    <th>Región</th>
                    <th>Comuna</th>
                    <th>Negocio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $branch)
                    <tr>
                    <td>{{ $branch['id'] }}</td>
                    <td>{{ $branch['schedule'] }}</td>
                    <td>{{ $branch['street'] }}</td>
                    <td>{{ $branch['region'] }}</td>
                    <td>{{ $branch['commune'] }}</td>
                    <td>{{ $branch['business'] }}</td>
                        <td>
                            <a href="{{ route('sucursales.edit', $branch['id'] ) }}" class="btn btn-warning btn-sm">Editar</a>

                            <form action="{{ route('sucursales.destroy', $branch['id'] ) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>

                            <a href="{{ route('servicios.show', $branch['id'] ) }}" class="btn btn-warning btn-sm">Servicios</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No hay sucursales registradas.</div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
</script>
</body>
</html>
