@extends('tenant.layouts.admin')

@section('title', 'Editar Sucursal')
@section('page_title', 'Editar Sucursal')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container mt-5">
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-pen me-2"></i>Editar Sucursal
    </div>
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('sucursales.update', $branch->id_branch) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nombre sucursal</label>
            <input type="text" name="name_branch_offices" class="form-control" value="{{ old('name_branch_offices', $branch->name_branch_offices) }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Horario</label>
            <input type="text" name="schedule" class="form-control" value="{{ old('schedule', $branch->schedule) }}" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Calle</label>
            <input type="text" name="street" class="form-control" value="{{ old('street', $branch->street) }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Región</label>
            <select name="region" id="region-select" class="selectpicker form-control" data-live-search="true" required>
              <option value="">Seleccione región</option>
              @foreach ($locacion->pluck('location_region')->filter()->unique('id') as $region)
                <option value="{{ $region->name_region }}"
                    {{ old('region', $branch->branch_office_location?->location_region?->name_region) == $region->name_region ? 'selected' : '' }}>
                  {{ $region->name_region }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Comuna</label>
            <select name="id_location" id="commune-select" class="selectpicker form-control" data-live-search="true" required>
              <option value="">Seleccione comuna</option>
              @foreach ($locacion as $loc)
                <option value="{{ $loc->id_location }}"
                        data-region="{{ $loc->location_region?->name_region }}"
                        {{ $branch->id_location == $loc->id_location ? 'selected' : '' }}>
                  {{ $loc->commune }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6" style="display: none;">
            <label class="form-label">Negocio</label>
            <select name="id_business" class="selectpicker form-control" data-live-search="true">
              @foreach ($business as $b)
                <option value="{{ $b->id_business }}" {{ $branch->id_business == $b->id_business ? 'selected' : '' }}>
                  {{ $b->name_business }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row justify-content-end mt-4">
            <div class="col-auto">
              <a href="{{ route('sucursales.index') }}" class="btn btn-secondary mr-2">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    $('.selectpicker').selectpicker();

    const regionSelect = document.getElementById('region-select');
    const communeSelect = document.getElementById('commune-select');
    const form = document.querySelector('form');

    const updateComunas = () => {
      const selectedRegion = regionSelect.value;
      communeSelect.disabled = !selectedRegion;

      Array.from(communeSelect.options).forEach(option => {
        if (!option.value) return;
        option.hidden = option.dataset.region !== selectedRegion;
      });

      if (communeSelect.selectedOptions.length && communeSelect.selectedOptions[0].hidden) {
        communeSelect.value = '';
      }

      $('.selectpicker').selectpicker('refresh');
    };

    regionSelect.addEventListener('change', updateComunas);
    updateComunas();

    form.addEventListener('submit', (e) => {
      if (!communeSelect.value) {
        e.preventDefault();
        alert('Debe seleccionar una comuna válida para continuar.');
        communeSelect.focus();
      }
    });
  });
</script>
@endpush
