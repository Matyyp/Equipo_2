<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Información de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Nuevo Contacto</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('informacion_contacto.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="type_contact" class="form-label">Tipo de Contacto</label>
            <input type="text" name="type_contact" class="form-control" value="{{ old('type_contact') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_contact" class="form-label">Dato de Contacto</label>
            <input type="text" name="data_contact" class="form-control" value="{{ old('data_contact') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('informacion_contacto.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
