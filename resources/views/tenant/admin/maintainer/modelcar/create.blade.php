@extends('tenant.layouts.admin')

@section('title', 'Registrar Modelo')
@section('page_title', 'Registrar Nuevo Modelo')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Revisa los siguientes errores:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('modelo.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="name_model" class="form-label">Nombre del Modelo</label>
                <input type="text" name="name_model" id="name_model" class="form-control"
                       value="{{ old('name_model') }}" required>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('modelo.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
