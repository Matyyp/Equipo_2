<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Información de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Información de Contacto</h1>

    <a href="{{ route('informacion_contacto.create') }}" class="btn btn-success mb-3">Nuevo Contacto</a>

    @if ($data->count())
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tipo de Contacto</th>
                    <th>Dato</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->type_contact }}</td>
                        <td>{{ $item->data_contact }}</td>
                        <td>
                            <a href="{{ route('informacion_contacto.edit', $item->id_contact_information) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('informacion_contacto.destroy', $item->id_contact_information) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No hay contactos registrados.</div>
    @endif
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar contacto?',
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
