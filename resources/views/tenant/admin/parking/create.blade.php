@extends('tenant.layouts.admin')

@section('title', 'Registro de Estacionamiento')
@section('page_title', 'Registro de Estacionamiento')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
  .loading-spinner { display: none; color: #0d6efd; margin-left: 10px; }
  .form-disabled {
    opacity: 0.5;
    pointer-events: none;
  }
  input[readonly] {
    background-color: #e9ecef !important;
    color: #000;
  }
</style>
@endpush

@section('content')
<div class="container mt-5">
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-car me-2"></i>Ingreso de Vehículo al estacionamiento
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @if($parkingServices->isEmpty())
        <div class="alert alert-warning">
          No hay servicios de estacionamiento disponibles. 
          Por favor, activa uno antes de registrar vehículos.
          <br>
          <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-light mt-2">
            <i class="fas fa-store"></i> Ir a sucursal
          </a>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            $('#form-register :input:not([name="_token"])').prop('disabled', true);
          });
        </script>
      @elseif(!$hasContract)
        <div class="alert alert-warning">
          No hay contratos activos asociados a los servicios disponibles. 
          Por favor, cree uno antes de registrar vehículos.
          <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-light mt-2">
            <i class="fas fa-store"></i> Ir a sucursal
          </a>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            $('#form-register :input:not([name="_token"])').prop('disabled', true);
          });
        </script>
      @endif

      <form action="{{ route('estacionamiento.store') }}" method="POST" autocomplete="off" id="form-register">
        @csrf

        <div class="row mb-3">
          <div class="col-md-4 position-relative">
            <label for="plate" class="form-label">Patente</label>
            <div class="input-group">
              <input type="text" id="plate" name="plate" class="form-control" placeholder="Ej: AB123C" minlength="6" maxlength="6" pattern="[A-Z0-9]{6}" required>
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <div id="plateAlert" class="mt-2"></div>
            <div id="plateLoading" class="loading-spinner position-absolute end-0 top-50 me-3">
              <i class="fas fa-spinner fa-spin"></i>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="number" id="phone" name="phone" class="form-control" min="100000000" max="999999999" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="start_date" class="form-label">Fecha de Inicio</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="end_date" class="form-label">Fecha de Término</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="arrival_km" class="form-label">Km Entrada (opcional)</label>
            <input type="number" id="arrival_km" name="arrival_km" class="form-control" min="0">
          </div>
          <div class="col-md-6">
            <label for="km_exit" class="form-label">Km Salida (opcional)</label>
            <input type="number" id="km_exit" name="km_exit" class="form-control" min="0">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="brand_name" class="form-label">Marca</label>
            <input type="text" id="brand_name" name="brand_name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="model_name" class="form-label">Modelo</label>
            <input type="text" id="model_name" name="model_name" class="form-control" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="service_id" class="form-label">Tipo de Estacionamiento</label>
            <select id="service_id" name="service_id" class="selectpicker form-control" data-live-search="true" required>
              <option value="">—</option>
              @foreach($parkingServices as $svc)
                <option value="{{ $svc->id_service }}">{{ $svc->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-check mb-4">
          <input type="checkbox" id="wash_service" name="wash_service" class="form-check-input">
          <label for="wash_service" class="form-check-label">Incluye Servicio de Lavado</label>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="reset" class="btn btn-secondary me-md-2"><i class="fas fa-eraser me-1"></i> Limpiar</button>
          <button type="submit" class="btn btn-primary" id="submit-btn"><i class="fas fa-save me-1"></i> Guardar</button>
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

  const plateInput = $('#plate');
  const phoneInput = $('#phone');
  const nameInput = $('#name');
  const brandInput = $('#brand_name');
  const modelInput = $('#model_name');
  const plateAlert = $('#plateAlert');
  const plateLoading = $('#plateLoading');
  const contratoUrl = '{{ route("estacionamiento.checkContrato") }}';
  const submitBtn = $('#submit-btn');
  let debounceTimer;
  let plateChecked = false;

  function showAlert(type, message) {
    plateAlert.removeClass().addClass(`alert alert-${type} mt-2`).text(message);
  }

  function clearAssociatedFields() {
    nameInput.val('').prop('readonly', false);
    phoneInput.val('').prop('readonly', false);
    brandInput.val('').prop('readonly', false);
    modelInput.val('').prop('readonly', false);
  }

  function searchByPlate(plate) {
    if (!plate) return;
    plateLoading.show();
    plateChecked = false;

    $.ajax({
      url: '{{ route("estacionamiento.search") }}',
      method: 'GET',
      data: { plate },
      success: function(response) {
        if (response.found) {
          if (response.parked) {
            showAlert('danger', 'Este vehículo ya se encuentra estacionado.');
            clearAssociatedFields();
            plateChecked = false;
          } else {
            nameInput.val(response.name || '').prop('readonly', true);
            phoneInput.val(response.phone || '').prop('readonly', true);
            brandInput.val(response.brand || '').prop('readonly', true);
            modelInput.val(response.model || '').prop('readonly', true);
            showAlert('success', 'Vehículo disponible. Puede continuar con el ingreso.');
            plateChecked = true;
          }
        } else {
          showAlert('warning', 'La patente no existe en los registros. Ingrese los datos manualmente.');
          clearAssociatedFields();
          plateChecked = true;
        }
      },
      complete: function() {
        plateLoading.hide();
      }
    });
  }

  plateInput.on('input', function() {
    const plate = $(this).val().trim().toUpperCase();
    $(this).val(plate);
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => searchByPlate(plate), 500);
  });

  phoneInput.on('input', function () {
    const phone = $(this).val().trim();

    if (phone.length === 9) {
      $.ajax({
        url: '{{ route("estacionamiento.searchPhone") }}',
        method: 'GET',
        data: { phone },
        success: function (res) {
          if (res.found) {
            nameInput.val(res.name);
            showAlert('info', 'Este número ya está registrado a nombre de ' + res.name);
            $('#submit-btn').prop('disabled', true);
          } else {
            nameInput.val('');
            $('#submit-btn').prop('disabled', false);
          }
        },
        error: function () {
          showAlert('danger', 'Error al verificar el número. Intente de nuevo.');
          $('#submit-btn').prop('disabled', true);
        }
      });
    } else {
      $('#submit-btn').prop('disabled', true);
    }
  });

  $('#service_id').on('changed.bs.select', function () {
    const serviceId = $(this).val();
    if (!serviceId) return;

    $.ajax({
      url: contratoUrl,
      method: 'GET',
      data: { service_id: serviceId },
      success: function (res) {
        $('#contract-warning').remove();
        if (!res.contract_exists) {
          submitBtn.prop('disabled', true);
          submitBtn.after('<div id="contract-warning" class="text-danger mt-2">Este servicio no tiene contrato activo.</div>');
        } else {
          submitBtn.prop('disabled', false);
        }
      },
      error: function () {
        alert('No se pudo verificar el contrato del servicio.');
      }
    });
  });

  $('#form-register').on('submit', function(e) {
    if (!plateChecked) {
      e.preventDefault();
      alert('Debe verificar la patente antes de guardar.');
    }
  });

  const today = new Date().toISOString().slice(0, 10);
  $('#start_date').attr('min', today);
  $('#start_date').on('change', function() {
    $('#end_date').attr('min', $(this).val());
  });
});
</script>
@endpush
