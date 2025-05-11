@extends('tenant.layouts.admin')

@section('title', 'Registrar Auto')
@section('page_title', 'Registrar Auto')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-car me-2"></i>
      <h5 class="mb-0">Registrar Auto</h5>
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

      <form action="{{ route('autos.store') }}" method="POST">
        @csrf

        {{-- Patente --}}
        <div class="form-group mb-3">
          <label for="patent">Patente</label>
          <input type="text" name="patent" class="form-control" value="{{ old('patent') }}" required>
        </div>

        {{-- Marca --}}
        <div class="form-group mb-3">
          <label for="brand_id">Marca</label>
          <select name="brand_id" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione una marca</option>
            @foreach($brands as $brand)
              <option value="{{ $brand->id_brand }}" {{ old('brand_id') == $brand->id_brand ? 'selected' : '' }}>
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
              <option value="{{ $model->id_model }}" {{ old('model_id') == $model->id_model ? 'selected' : '' }}>
                {{ $model->name_model }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Arriendo --}}
        <div class="form-group mb-3">
          <label for="is_rentable">¿Está disponible para arriendo?</label>
            <select name="is_rentable" id="is_rentable" class="form-select selectpicker" data-live-search="true" required>
            <option value="">Seleccione</option>
            <option value="1" {{ old('is_rentable') == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('is_rentable') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        {{-- Valor Arriendo --}}
        <div class="form-group mb-3 {{ old('is_rentable') == '1' ? '' : 'd-none' }}" id="rent_value_group">
          <label for="value_rent">Valor Arriendo</label>
          <input type="number" name="value_rent" id="value_rent" class="form-control"
                 value="{{ old('value_rent') }}" min="0">
        </div>

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('autos.index') }}" class="btn btn-secondary mr-2">
              <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Guardar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
  $(document).ready(function () {
    $('.selectpicker').selectpicker();

    $('#is_rentable').on('change', function () {
      if ($(this).val() == "1") {
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

