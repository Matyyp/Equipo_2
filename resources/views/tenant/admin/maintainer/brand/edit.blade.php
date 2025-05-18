@extends('tenant.layouts.admin')

@section('title', 'Editar Marca')
@section('page_title', 'Editar Marca')

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-edit mr-2"></i> Editar Marca</div>
    </div>

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

      {{-- Formulario de edición --}}
      <form action="{{ route('marca.update', $brand->id_brand) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="name_brand" class="form-label">Nombre de la Marca</label>
          <input type="text" name="name_brand" id="name_brand" class="form-control" value="{{ old('name_brand', $brand->name_brand) }}" required>
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('marca.index') }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Actualizar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
