<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Accesorio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Accesorio</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('accesorio.update', $accessory->id_accessory) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name_accessory" class="form-label">Nombre del Accesorio</label>
            <input type="text" name="name_accessory" id="name_accessory" class="form-control" value="{{ old('name_accessory', $accessory->name_accessory) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('accesorio.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
