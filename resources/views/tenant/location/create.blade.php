<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Ubicación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Ingresar Nueva Ubicación</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error:</strong> Corrige los siguientes errores:<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('locacion.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="region" class="form-label">Región</label>
            <input type="text" name="region" class="form-control" id="region" value="{{ old('region') }}" required>
        </div>

        <div class="mb-3">
            <label for="commune" class="form-label">Comuna</label>
            <input type="text" name="commune" class="form-control" id="commune" value="{{ old('commune') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Ubicación</button>
        <a href="{{ route('locacion.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
