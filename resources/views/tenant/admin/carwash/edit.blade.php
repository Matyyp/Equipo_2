@extends('tenant.layouts.admin')

@section('title', 'Editar Lavado')
@section('page_title', 'Editar Servicio de Lavado')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <h5 class="mb-0">
        <i class="fas fa-edit me-2"></i> Editar Lavado: {{ $lavado->name }}
      </h5>
    </div>

    <div class="card-body">
      {{-- Errores de validación --}}
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

      <form method="POST" action="{{ route('lavados.update', $lavado->id_service) }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
          <label for="name">Nombre del Lavado</label>
          <input type="text" name="name" id="name" class="form-control"
                 value="{{ old('name', $lavado->name) }}" readonly>
        </div>

        <div class="form-group mb-3">
          <label for="price_net">Precio Neto</label>
          <input type="number" name="price_net" id="price_net" class="form-control"
                 value="{{ old('price_net', $lavado->price_net) }}" required>
        </div>

        <div class="form-group d-flex justify-content-between">
          <a href="{{ route('lavados.show', $lavado->id_branch_office) }}" class="btn btn-secondary">
            Cancelar
          </a>

          <button type="submit" class="btn btn-primary">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
