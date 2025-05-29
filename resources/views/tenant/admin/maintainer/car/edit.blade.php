@extends('tenant.layouts.admin')

@section('title', 'Editar Auto')
@section('page_title', 'Editar Información del Auto')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-edit mr-2"></i> Editar Información del Auto</div>
    </div>

    <div class="card-body">
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

      <form action="{{ route('autos.update', ['auto' => $car->id_car]) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Patente --}}
        <div class="form-group mb-3">
          <label for="patent">Patente</label>
          <input type="text" name="patent" class="form-control" value="{{ old('patent', $car->patent) }}" required>
        </div>

        {{-- Marca --}}
        <div class="form-group mb-3">
          <label for="brand_id">Marca</label>
          <select name="brand_id" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione una marca</option>
            @foreach($brands as $brand)
              <option value="{{ $brand->id_brand }}"
                {{ old('brand_id', $car->id_brand) == $brand->id_brand ? 'selected' : '' }}>
                {{ $brand->name_brand }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Modelo --}}
        <div class="form-group mb-3">
          <label for="model_id">Modelo</label>
          <select name="model_id" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione un modelo</option>
            @foreach($models as $model)
              <option value="{{ $model->id_model }}"
                {{ old('model_id', $car->id_model) == $model->id_model ? 'selected' : '' }}>
                {{ $model->name_model }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Botones --}}
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('autos.index') }}" class="btn btn-secondary mr-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              Actualizar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endpush
