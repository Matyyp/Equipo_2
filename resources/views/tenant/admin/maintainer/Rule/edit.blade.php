<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Regla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Regla</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('reglas.update', $rule->id_rule) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la Regla</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $rule->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $rule->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('reglas.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
