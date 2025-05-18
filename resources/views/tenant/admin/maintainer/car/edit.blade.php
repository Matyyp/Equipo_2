@extends('tenant.layouts.admin')

@section('title', 'Editar Auto')
@section('page_title', 'Editar Información del Auto')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container px-3 px-md-5 mt-4">
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

        {{-- ¿Está disponible para arriendo? --}}
        <div class="form-group mb-3">
          <label for="is_rentable">¿Está disponible para arriendo?</label>
          <select name="is_rentable" id="is_rentable" class="form-select selectpicker" required>
            <option value="">Seleccione</option>
            <option value="1" {{ old('is_rentable', $car->is_rentable) == 1 ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('is_rentable', $car->is_rentable) == 0 ? 'selected' : '' }}>No</option>
          </select>
        </div>

        {{-- Valor Arriendo --}}
        <div class="form-group mb-3 {{ old('is_rentable', $car->is_rentable) == 1 ? '' : 'd-none' }}" id="rent_value_group">
          <label for="value_rent">Valor Arriendo</label>
          <input type="number" name="value_rent" id="value_rent" class="form-control"
                value="{{ old('value_rent', $car->value_rent) }}"
                {{ old('is_rentable', $car->is_rentable) == 1 ? 'required' : '' }} min="0">
        </div>

        {{-- Botones --}}
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('autos.index') }}" class="btn btn-secondary mr-2">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
  $(document).ready(function () {
    $('.selectpicker').selectpicker();

    $('#is_rentable').on('change', function () {
      if ($(this).val() === "1") {
        $('#rent_value_group').removeClass('d-none');
        $('#value_rent').attr('required', true);
      } else {
        $('#rent_value_group').addClass('d-none');
        $('#value_rent').val('').removeAttr('required');
      }
    }).trigger('change');
  });
</script>
@endpush
