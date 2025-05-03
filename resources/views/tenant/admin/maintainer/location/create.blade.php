@extends('tenant.layouts.admin')

@section('title', 'Crear Ubicación')
@section('page_title', 'Ingresar Nueva Ubicación')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-map-location me-2"></i>Formulario de Registro
    </div>

    <div class="card-body">
      {{-- Mensajes de error --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>¡Ups!</strong> Corrige los siguientes errores:
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

        {{-- Región --}}
        <div class="form-group mb-3">
          <label for="region">Región</label>
          <select name="region" id="region" class="form-control @error('region') is-invalid @enderror" required>
            <option value="">Seleccione una región</option>
            @foreach($region as $r)
              <option value="{{ $r->id }}" {{ old('region') == $r->id ? 'selected' : '' }}>
                {{ $r->name_region }}
              </option>
            @endforeach
          </select>
          @error('region')
            <span class="invalid-feedback d-block">{{ $message }}</span>
          @enderror
        </div>

        {{-- Comuna --}}
        <div class="form-group mb-3">
          <label for="commune">Comuna</label>
          <input
            type="text"
            name="commune"
            id="commune"
            class="form-control @error('commune') is-invalid @enderror"
            value="{{ old('commune') }}"
            required
          >
          @error('commune')
            <span class="invalid-feedback d-block">{{ $message }}</span>
          @enderror
        </div>

        {{-- Botones --}}
        <div class="text-end">
          <a href="{{ route('locacion.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Guardar Ubicación
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
