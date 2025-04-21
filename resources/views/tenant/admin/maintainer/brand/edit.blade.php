<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Marca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Marca</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('marca.update', $brand->id_brand) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name_brand" class="form-label">Nombre de la Marca</label>
            <input type="text" name="name_brand" class="form-control" value="{{ old('name_brand', $brand->name_brand) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('marca.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
