@extends('tenant.layouts.admin')

@section('title', 'Editar Ingreso al Estacionamiento')
@section('page_title', 'Editar Ingreso al Estacionamiento')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit mr-2"></i>Editar Ingreso
    </div>

    <div class="card-body">
      @if($parkingServices->isEmpty())
        <div class="alert alert-warning">
          No hay servicios de estacionamiento disponibles.
          <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-success mt-2">
            <i class="fas fa-store"></i> Ir a sucursal
          </a>
        </div>
        <script>document.addEventListener('DOMContentLoaded', () => $('#edit-form :input:not([name="_token"])').prop('disabled', true));</script>
      @elseif(!$hasContract)
        <div class="alert alert-warning">
          No hay contratos activos asociados a los servicios disponibles.
          <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-success mt-2">
            <i class="fas fa-store"></i> Ir a sucursal
          </a>
        </div>
        <script>document.addEventListener('DOMContentLoaded', () => $('#edit-form :input:not([name="_token"])').prop('disabled', true));</script>
      @endif

      <form action="{{ route('estacionamiento.update', $parking->id_parking_register) }}" method="POST" id="edit-form">
        @csrf
        @method('PUT')

        <input type="hidden" id="original_phone" value="{{ $owner->number_phone }}">

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="plate">Patente</label>
            <input type="text" id="plate" name="plate" class="form-control" value="{{ $car->patent }}" readonly>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $owner->name }}" required>
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="phone">Teléfono</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ $owner->number_phone }}" maxlength="9" pattern="^[0-9]{9}$" required>
            <div id="phone-error" class="text-danger mt-1"></div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="start_date">Fecha de Inicio</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $parking->start_date }}" required>
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="end_date">Fecha de Término</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $parking->end_date }}" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="arrival_km">Km Entrada</label>
            <input type="number" id="arrival_km" name="arrival_km" class="form-control" value="{{ $parking->arrival_km }}" min="0">
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="km_exit">Km Salida</label>
            <input type="number" id="km_exit" name="km_exit" class="form-control" value="{{ $parking->km_exit }}" min="0">
          </div>
        </div>

        @role('SuperAdmin')
        <div class="form-group">
          <label for="branch_office_id">Sucursal</label>
          <select id="branch_office_id" name="branch_office_id" class="selectpicker form-control" data-live-search="true">
            <option value="">Seleccione una sucursal</option>
            @foreach($branches as $branch)
              <option value="{{ $branch->id_branch }}" {{ $branch->id_branch == $service->id_branch_office ? 'selected' : '' }}>
                {{ $branch->name_branch_offices }}
              </option>
            @endforeach
          </select>
        </div>
        @endrole

        <div class="form-group">
          <label for="service_id">Tipo de Estacionamiento</label>
          @role('SuperAdmin')
            <select id="service_id" name="service_id" class="selectpicker form-control" data-live-search="true" required>
              <option value="">Seleccione un servicio</option>
              @foreach($parkingServices as $svc)
                <option value="{{ $svc->id_service }}" {{ $svc->id_service == $service->id_service ? 'selected' : '' }}>
                  {{ $svc->name }}
                </option>
              @endforeach
            </select>
          @else
            <input type="text" class="form-control" value="{{ $service->name }}" readonly>
            <input type="hidden" name="service_id" value="{{ $service->id_service }}">
          @endrole
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="brand_name">Marca</label>
            <input type="text" id="brand_name" name="brand_name" class="form-control" value="{{ $brands }}" required>
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="model_name">Modelo</label>
            <input type="text" id="model_name" name="model_name" class="form-control" value="{{ $models }}" required>
          </div>
        </div>
        {{-- Lavado --}}
        <div class="form-check mb-3">
          <input type="hidden" name="wash_service" value="0">
          <input type="checkbox" id="wash_service" name="wash_service" class="form-check-input" value="1" {{ $lavadoAsignado ? 'checked' : '' }}>
          <label for="wash_service" class="form-check-label">Incluye Servicio de Lavado</label>
        </div>

        <div id="wash-type-group" class="form-group" style="display: none;">
          <label for="wash_type">Tipo de Lavado</label>
          <select name="wash_type" id="wash_type" class="form-control">
            <option value="">Seleccione un tipo de lavado</option>
            @foreach($carWashServices as $lavado)
              <option value="{{ $lavado->id_service }}" {{ $lavadoAsignado == $lavado->id_service ? 'selected' : '' }}>
                {{ $lavado->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('estacionamiento.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-warning" id="submit-btn">
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
  document.addEventListener('DOMContentLoaded', () => {
  $('.selectpicker').selectpicker();

  const phoneUrl = '{{ route("estacionamiento.searchPhone") }}';
  const phoneInput = document.getElementById('phone');
  const originalPhone = document.getElementById('original_phone').value;
  const form = document.getElementById('edit-form');
  const phoneError = document.getElementById('phone-error');
  const submitBtn = document.getElementById('submit-btn');

  let phoneValid = true;

  phoneInput.addEventListener('input', function () {
    const newPhone = phoneInput.value.trim();
    phoneError.textContent = '';
    phoneInput.classList.remove('is-invalid');
    phoneValid = true;

    if (newPhone.length === 9 && newPhone !== originalPhone) {
      fetch(`${phoneUrl}?phone=${newPhone}`)
        .then(res => res.json())
        .then(data => {
          if (data.found) {
            phoneError.textContent = `El teléfono ya pertenece a ${data.name}.`;
            phoneInput.classList.add('is-invalid');
            phoneValid = false;
          }
        })
        .catch(() => {
          phoneError.textContent = 'No se pudo verificar el número.';
          phoneInput.classList.add('is-invalid');
          phoneValid = false;
        });
    }
  });

  form.addEventListener('submit', function (e) {
    if (!phoneValid) {
      e.preventDefault();
      phoneInput.focus();
    }
  });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $checkbox = $('#wash_service');
  const $group = $('#wash-type-group');
  const $select = $('#wash_type');
  let selectedBranchId = $('#branch_office_id').val(); // inicial

  function loadLavados(branchId, selectedId = null) {
    if (!branchId) return;

    $.ajax({
      url: '{{ route("lavados.sucursal") }}',
      method: 'GET',
      data: { id_branch_office: branchId },
      success: function (data) {
        $select.empty().append('<option value="">Seleccione un tipo de lavado</option>');
        data.forEach(item => {
          const selected = selectedId && item.id_service == selectedId ? 'selected' : '';
          $select.append(`<option value="${item.id_service}" ${selected}>${item.name}</option>`);
        });
      },
      error: function () {
        alert('Error al cargar tipos de lavado.');
      }
    });
  }

  $checkbox.on('change', function () {
    const checked = $(this).is(':checked');
    selectedBranchId = $('#branch_office_id').val();

    if (checked) {
      if (!selectedBranchId) {
        alert('Debe seleccionar una sucursal antes de incluir lavado.');
        $(this).prop('checked', false);
        return;
      }

      $group.show();
      loadLavados(selectedBranchId);
    } else {
      $group.hide();
      $select.empty();
    }
  });

  // Si ya estaba marcado al cargar la página
  if ($checkbox.is(':checked')) {
    $group.show();
    loadLavados(selectedBranchId, '{{ $lavadoAsignado }}');
  }

  // Al cambiar de sucursal (solo si checkbox está marcado)
  $('#branch_office_id').on('changed.bs.select', function () {
    selectedBranchId = $(this).val();
    if ($checkbox.is(':checked') && selectedBranchId) {
      loadLavados(selectedBranchId);
    }
  });
});
</script>

@endpush