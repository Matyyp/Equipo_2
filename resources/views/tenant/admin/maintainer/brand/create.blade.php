@extends('tenant.layouts.admin')

@section('title', 'Crear Marca')
@section('page_title', 'Registrar Nueva Marca')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de registro --}}
        <form action="{{ route('marca.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="name_brand">Nombre de la Marca</label>
                <input type="text" name="name_brand" class="form-control" value="{{ old('name_brand') }}" required>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('marca.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
