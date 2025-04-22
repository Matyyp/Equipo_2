@extends('tenant.layouts.admin')

@section('title', 'Registrar Contacto')
@section('page_title', 'Registrar Nuevo Contacto')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Corrige los siguientes errores:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de creación --}}
        <form action="{{ route('informacion_contacto.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="type_contact">Tipo de Contacto</label>
                <input type="text" name="type_contact" id="type_contact" class="form-control"
                       value="{{ old('type_contact') }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="data_contact">Dato de Contacto</label>
                <input type="text" name="data_contact" id="data_contact" class="form-control"
                       value="{{ old('data_contact') }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('informacion_contacto.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
