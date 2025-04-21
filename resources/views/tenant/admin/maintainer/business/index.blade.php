<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informacion del Negocio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Listado de Negocios</h1>

    {{-- Solo mostrar el botón si NO hay empresas --}}
    @if ($data->count() === 0)
        <a href="{{ route('empresa.create') }}" class="btn btn-success mb-3">Ingresar Negocio</a>
    @else
        <div class="alert alert-info mb-3">
            Ya hay una empresa registrada. No se pueden agregar más.
        </div>
    @endif

    @if($data->count())
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre Empresa</th>
                        <th>Datos de Transferencia</th>
                        <th>Logo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $business)
                        <tr>
                            <td>{{ $business->name_business }}</td>
                            <td>{{ $business->electronic_transfer }}</td>
                            <td>
                                @if ($business->logo)
                                    <img src="{{ request()->getSchemeAndHttpHost() . '/storage/tenants/' . request()->getHost() . '/imagenes/' . $business->logo }}" alt="Logo del Negocio" class="img-thumbnail" width="100">
                                @else
                                    <p>No hay logo disponible.</p>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('empresa.edit', $business->id_business) }}" class="btn btn-warning btn-sm mb-1">Editar</a>
                                
                                {{-- Botón de eliminar con confirmación --}}
                                <form action="{{ route('empresa.destroy', $business->id_business) }}" method="POST" class="d-inline delete-form">
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
        <div class="alert alert-info">No hay negocios registrados.</div>
    @endif
</div>

<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el negocio registrado.",
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
