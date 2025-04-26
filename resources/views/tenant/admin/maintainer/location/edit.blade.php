@extends('tenant.layouts.admin')
@section('title', 'Editar Ubicación')
@section('page_title', 'Editar Ubicación')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>¡Ups!</strong> Corrige los siguientes errores:<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de edición --}}
        <form action="{{ route('locacion.update', $location->id_location) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="region">Región</label>
                <input type="text" name="region" class="form-control" id="region"
                       value="{{ old('region', $location->region) }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="commune">Comuna</label>
                <input type="text" name="commune" class="form-control" id="commune"
                       value="{{ old('commune', $location->commune) }}" required>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Ubicación
                </button>
                <a href="{{ route('locacion.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
