@extends('tenant.layouts.admin')

@section('title', 'Registro de Estacionamiento')
@section('page_title', 'Registro de Estacionamiento')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
  .loading-spinner {
    display: none;
    color: #0d6efd;
    margin-left: 10px;
  }
  .loading-spinner.active {
    display: inline-block;
  }
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
<div class="container px-3 px-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
      <i class="fas fa-car me-2"></i>
      <h5 class="mb-0">Ingreso de Vehículo al Estacionamiento</h5>
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
          <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-success mt-2">
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
          <a href="{{ route('sucursales.index') }}" class="btn btn-sm btn-success mt-2">
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

        <div class="form-group col-3">
          <label for="plate">Patente</label>
          <div class="input-group">
            <input type="text" id="plate" name="plate" class="form-control" placeholder="Ej: AB123C" minlength="6" maxlength="6" pattern="[A-Z0-9]{6}" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
          </div>
          <div id="plateAlert" class="mt-2"></div>
          <div id="plateLoading" class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="phone">Teléfono</label>
            <input type="number" id="phone" name="phone" class="form-control" min="100000000" max="999999999" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="start_date">Fecha de Inicio</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required>
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="end_date">Fecha de Término</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="arrival_km">Km Entrada (opcional)</label>
            <input type="number" id="arrival_km" name="arrival_km" class="form-control" min="0">
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="km_exit">Km Salida (opcional)</label>
            <input type="number" id="km_exit" name="km_exit" class="form-control" min="0">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="brand_name">Marca</label>
            <input type="text" id="brand_name" name="brand_name" class="form-control" required>
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="model_name">Modelo</label>
            <input type="text" id="model_name" name="model_name" class="form-control" required>
          </div>
        </div>

        @role('SuperAdmin')
        <div class="form-group">
          <label for="branch_office_id">Sucursal</label>
          <select id="branch_office_id" name="branch_office_id" class="selectpicker form-control" data-live-search="true" required>
            <option value="">—</option>
            @foreach($branches as $branch)
              <option value="{{ $branch->id_branch }}">{{ $branch->name_branch_offices }}</option>
            @endforeach
          </select>
        </div>
        @endrole

        <div class="form-group">
          <label for="service_id">Tipo de Estacionamiento</label>
          <select id="service_id" name="service_id" class="selectpicker form-control" data-live-search="true" required>
            <option value="">—</option>
            @unless(auth()->user()->hasRole('SuperAdmin'))
              @foreach($parkingServices as $svc)
                @role('SuperAdmin')
                  $('#service_id').prop('disabled', true).selectpicker('refresh');
                @endrole

                <option value="{{ $svc->id_service }}">{{ $svc->name }}</option>
              @endforeach
            @endunless
          </select>
        </div>

        <div class="form-check mb-4">
          <input type="checkbox" id="wash_service" name="wash_service" class="form-check-input">
          <label for="wash_service" class="form-check-label">Incluye Servicio de Lavado</label>
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <button type="reset" class="btn btn-secondary">
              <i class="fas fa-eraser mr-1"></i> Limpiar
            </button>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary" id="submit-btn">
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
  @role('SuperAdmin')
    $('#service_id').prop('disabled', true).selectpicker('refresh');
  @endrole


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
    plateLoading.addClass('active');
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
        plateLoading.removeClass('active');
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
    plateAlert.removeClass().text('');

    if (phone.length === 9) {
      $.ajax({
        url: '{{ route("estacionamiento.searchPhone") }}',
        method: 'GET',
        data: { phone },
        success: function (res) {
          if (res.found) {
            showAlert('info', 'Este número ya está registrado a nombre de ' + res.name);
            submitBtn.prop('disabled', true);
          } else {
            nameInput.val('');
            submitBtn.prop('disabled', false);
          }
        },
        error: function () {
          showAlert('danger', 'Error al verificar el número. Intente de nuevo.');
          submitBtn.prop('disabled', true);
        }
      });
    } else {
      submitBtn.prop('disabled', true);
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

  @role('SuperAdmin')
  $('#branch_office_id').on('changed.bs.select', function () {
    const branchId = $(this).val();
    const serviceSelect = $('#service_id');

    if (!branchId) {
      serviceSelect.prop('disabled', true).selectpicker('refresh');
      return;
    } else {
      serviceSelect.prop('disabled', false).selectpicker('refresh');
    }

    serviceSelect.empty().append('<option value="">Cargando...</option>').selectpicker('refresh');

    $.ajax({
      url: '{{ route("estacionamiento.getServicesByBranch") }}',
      method: 'GET',
      data: { branch_id: branchId },
      success: function (res) {
        serviceSelect.empty().append('<option value="">—</option>');
        if (res.length === 0) {
          serviceSelect.append('<option disabled>No hay servicios disponibles</option>');
        } else {
          res.forEach(service => {
            serviceSelect.append(`<option value="${service.id_service}">${service.name}</option>`);
          });
        }
        serviceSelect.selectpicker('refresh');
      },
      error: function () {
        alert('No se pudieron cargar los servicios para la sucursal seleccionada.');
      }
    });
  });
  @endrole


  $('#form-register').on('submit', function(e) {
    if (!plateChecked) {
      e.preventDefault();
      alert('Debe verificar la patente antes de guardar.');
    } else {
      submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');
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
