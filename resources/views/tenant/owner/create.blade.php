<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Propietario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Nuevo Propietario</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('dueños.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="type_owner" class="form-label">Tipo de Propietario</label>
            <select name="type_owner" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="cliente" {{ old('type_owner') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                <option value="empresa" {{ old('type_owner') == 'empresa' ? 'selected' : '' }}>Empresa</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Apellido</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="number_phone" class="form-label">Número de Teléfono</label>
            <input type="text" name="number_phone" class="form-control" value="{{ old('number_phone') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('dueños.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
