<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Propietarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Listado de Propietarios</h1>

    <a href="{{ route('dueños.create') }}" class="btn btn-success mb-3">Nuevo Propietario</a>

    @if ($owner->count())
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($owner as $owner)
                    <tr>
                        <td>{{ $owner->type_owner }}</td>
                        <td>{{ $owner->name }}</td>
                        <td>{{ $owner->last_name }}</td>
                        <td>{{ $owner->number_phone }}</td>
                        <td>
                            <a href="{{ route('dueños.edit', $owner->id_owner) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('dueños.destroy', $owner->id_owner) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            <a href="{{ route('asociado.show', $owner->id_owner) }}" class="btn btn-primary btn-sm">
                                Ver autos asociados
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No hay propietarios registrados.</div>
    @endif
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar propietario?',
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
