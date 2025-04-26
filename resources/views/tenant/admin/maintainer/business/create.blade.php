@extends('tenant.layouts.admin')

@section('title', 'Ingresar Negocio')
@section('page_title', 'Ingresar Nuevo Negocio')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Hay algunos problemas con tu entrada.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de creación --}}
        <form action="{{ route('empresa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="name_business">Nombre de la Empresa</label>
                <input type="text" name="name_business" class="form-control" id="name_business"
                       value="{{ old('name_business') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="electronic_transfer">Datos de Transferencia</label>
                <textarea name="electronic_transfer" class="form-control" id="electronic_transfer"
                          rows="3" required>{{ old('electronic_transfer') }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label for="logo">Logo</label>
                <input type="file" name="logo" class="form-control" id="logo" accept="image/*">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Negocio
                </button>
                <a href="{{ route('empresa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
