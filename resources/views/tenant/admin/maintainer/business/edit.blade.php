<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Negocio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Editar Negocio</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Hubo algunos errores con tus datos.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empresa.update', $business->id_business) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name_business" class="form-label">Nombre de la Empresa</label>
            <input type="text" id="name_business" name="name_business" class="form-control" value="{{ old('name_business', $business->name_business) }}" required>
        </div>

        <div class="mb-3">
            <label for="electronic_transfer" class="form-label">Datos de Transferencia</label>
            <input type="text" id="electronic_transfer" name="electronic_transfer" class="form-control" value="{{ old('electronic_transfer', $business->electronic_transfer) }}" required>
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Logo (opcional)</label>
            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
            @if ($business->logo)
                <p class="mt-2">Logo actual:</p>
                <img src="{{ request()->getSchemeAndHttpHost() . '/storage/tenants/' . request()->getHost() . '/imagenes/' . $business->logo }}" alt="Logo" class="img-thumbnail" width="100">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Empresa</button>
        <a href="{{ route('empresa.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
