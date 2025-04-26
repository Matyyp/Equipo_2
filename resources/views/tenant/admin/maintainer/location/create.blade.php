@extends('tenant.layouts.admin')
@section('title', 'Crear Ubicación')
@section('page_title', 'Ingresar Nueva Ubicación')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Mensajes de error --}}
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

        {{-- Formulario --}}
        <form action="{{ route('locacion.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="region">Región</label>
                <input type="text" name="region" id="region" class="form-control" value="{{ old('region') }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="commune">Comuna</label>
                <input type="text" name="commune" id="commune" class="form-control" value="{{ old('commune') }}" required>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Ubicación
                </button>
                <a href="{{ route('locacion.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
