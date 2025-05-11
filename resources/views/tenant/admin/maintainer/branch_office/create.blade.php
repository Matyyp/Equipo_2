@extends('tenant.layouts.admin')

@section('title', 'Crear Sucursal')
@section('page_title', 'Registrar Nueva Sucursal')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container mt-5">
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-store me-2"></i>Registro de Nueva Sucursal
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

      <form action="{{ route('sucursales.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nombre sucursal</label>
            <input type="text" name="name_branch_offices" class="form-control" value="{{ old('name_branch_offices') }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Horario</label>
            <input type="text" name="schedule" class="form-control" value="{{ old('schedule') }}" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Calle</label>
            <input type="text" name="street" class="form-control" value="{{ old('street') }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Región</label>
            <select id="region-select" class="selectpicker form-control" data-live-search="true" required>
              <option value="">Seleccione región</option>
              @foreach ($region as $r)
                <option value="{{ $r->id }}">{{ $r->name_region }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Comuna</label>
            <select name="id_location" id="commune-select" class="selectpicker form-control" data-live-search="true" required disabled>
              <option value="">Seleccione comuna</option>
              @foreach ($locacion as $loc)
                <option value="{{ $loc->id_location }}" data-region="{{ $loc->id_region }}">
                  {{ $loc->commune }}
                </option>
              @endforeach
            </select>
          </div>

          <input type="hidden" name="id_business" value="{{ $business->id_business }}">


        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <a href="{{ route('sucursales.index') }}" class="btn btn-secondary me-md-2">
            <i class="fas fa-arrow-left me-1"></i> Volver
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Guardar
          </button>
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

    const updateComunaOptions = () => {
      const selectedRegion = regionSelect.value;
      const hasRegion = !!selectedRegion;
      communeSelect.disabled = !hasRegion;
      Array.from(communeSelect.options).forEach(option => {
        if (!option.value) return;
        option.hidden = option.dataset.region !== selectedRegion;
      });
      communeSelect.value = '';
      $('.selectpicker').selectpicker('refresh');
    }

    regionSelect.addEventListener('change', updateComunaOptions);
    updateComunaOptions();
  });
</script>
@endpush
