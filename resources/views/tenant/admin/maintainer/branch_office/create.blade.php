@extends('tenant.layouts.admin')

@section('title', 'Crear Sucursal')
@section('page_title', 'Registrar Nueva Sucursal')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container-fluid">
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-store mr-2"></i> Registro de Nueva Sucursal</div>
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

      <form action="{{ route('sucursales.store') }}" method="POST" autocomplete="off" id="sucursal-form">
        @csrf

        {{-- Fila 1: Nombre y Horario --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Nombre sucursal</label>
            <input type="text" name="name_branch_offices" class="form-control" value="{{ old('name_branch_offices') }}" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Hora de apertura</label>
            <input type="time" id="hora_apertura" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Hora de cierre</label>
            <input type="time" id="hora_cierre" class="form-control" required>
          </div>
          <input type="hidden" name="schedule" id="schedule">
        </div>

        {{-- Fila 2: Dirección y Región --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Calle</label>
            <input type="text"
              name="street"
              id="street-input"
              class="form-control"
              value="{{ old('street') }}"
              required
              pattern="^.*\s\d{1,5}.*$"
              title="Debe incluir nombre de calle y número (ej: Calle Falsa 123)">

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

        {{-- Fila 3: Comuna y Teléfono --}}
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
          <div class="col-md-6">
            <label class="form-label">Teléfono de contacto</label>
            <input type="text"
              name="phone"
              class="form-control"
              value="{{ old('phone') }}"
              placeholder="Ej: +56912345678"
              required
              pattern="^\+569\d{8}$"
              title="Debe ingresar un número válido: +569 seguido de 8 dígitos (ej: +56912345678)">
          </div>
        </div>

        {{-- Fila 4: Correo electrónico --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Ej: correo@ejemplo.com">
          </div>
        </div>

        <input type="hidden" name="id_business" value="{{ $business->id_business }}">

        {{-- ALERTA de sucursal existente --}}
        <div id="alerta-sucursal" class="alert alert-warning d-none">
          ⚠️ Ya existe una sucursal registrada con esta dirección, región y comuna.
        </div>

        {{-- Botones --}}
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('sucursales.index') }}" class="btn btn-secondary mr-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" id="btn-guardar" class="btn btn-primary">
              Guardar
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
    const streetInput = document.getElementById('street-input');
    const alertaSucursal = document.getElementById('alerta-sucursal');
    const btnGuardar = document.getElementById('btn-guardar');

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
      checkSucursalExistente();
    }

    const checkSucursalExistente = () => {
      const street = streetInput.value.trim();
      const locationId = communeSelect.value;

      if (street && locationId) {
        $.get("{{ route('sucursal.verificar') }}", {
          street: street,
          id_location: locationId
        }, function (data) {
          if (data.existe) {
            alertaSucursal.classList.remove('d-none');
            btnGuardar.disabled = true;
          } else {
            alertaSucursal.classList.add('d-none');
            btnGuardar.disabled = false;
          }
        });
      } else {
        alertaSucursal.classList.add('d-none');
        btnGuardar.disabled = false;
      }
    }

    regionSelect.addEventListener('change', updateComunaOptions);
    communeSelect.addEventListener('change', checkSucursalExistente);
    streetInput.addEventListener('blur', checkSucursalExistente);
    updateComunaOptions();

    document.getElementById('sucursal-form').addEventListener('submit', function (e) {
      const apertura = document.getElementById('hora_apertura').value;
      const cierre = document.getElementById('hora_cierre').value;

      if (apertura && cierre) {
        document.getElementById('schedule').value = `${apertura} - ${cierre}`;
      }
    });
  });
</script>
@endpush
