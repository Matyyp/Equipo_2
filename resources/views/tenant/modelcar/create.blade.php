<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Modelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Nuevo Modelo</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('modelo.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name_model" class="form-label">Nombre del Modelo</label>
            <input type="text" name="name_model" class="form-control" value="{{ old('name_model') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('modelo.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
