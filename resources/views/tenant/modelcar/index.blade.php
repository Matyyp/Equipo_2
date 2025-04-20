<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modelos de Autos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Listado de Modelos</h1>

    <a href="{{ route('modelo.create') }}" class="btn btn-success mb-3">Nuevo Modelo</a>

    @if ($Modelcar->count())
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nombre del Modelo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Modelcar as $model)
                    <tr>
                        <td>{{ $model->name_model }}</td>
                        <td>
                            <a href="{{ route('modelo.edit', $model->id_model) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('modelo.destroy', $model->id_model) }}" method="POST" class="d-inline delete-form">
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
        <div class="alert alert-info">No hay modelos registrados.</div>
    @endif
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar este modelo?',
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
