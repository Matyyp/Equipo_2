{{-- resources/views/tenant/admin/parking/parking_register.blade.php --}}
@extends('tenant.layouts.admin')

@section('title', 'Registro de Estacionamiento')
@section('page_title', 'Registro de Estacionamiento')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .loading-spinner { display: none; color: #0d6efd; margin-left: 10px; }
    .is-valid { border-color: #198754 !important; }
    .is-invalid { border-color: #dc3545 !important; }
</style>
@endpush

@section('content')
<div class="container mt-5">
  <div class="card mb-4">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-car me-2"></i>Ingreso de Vehículo al estacionamiento
    </div>
    <div class="card-body">
      <form action="{{ route('estacionamiento.store') }}" method="POST" autocomplete="off">
        @csrf

        {{-- Patente --}}
        <div class="row mb-3">
          <div class="col-md-4 position-relative">
            <label for="plate" class="form-label">Patente</label>
            <div class="input-group">
              <input type="text" id="plate" name="plate" class="form-control" placeholder="Ej: AB123CD" maxlength="8" required>
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <div id="plateFeedback" class="invalid-feedback"></div>
            <div id="plateLoading" class="loading-spinner position-absolute end-0 top-50 me-3">
              <i class="fas fa-spinner fa-spin"></i>
            </div>
          </div>
        </div>

        {{-- Nombre y teléfono --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" id="name" name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="tel" id="phone" name="phone" class="form-control" required>
          </div>
        </div>

        {{-- Fechas --}}
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

        {{-- KMs --}}
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

        {{-- Marca y Modelo --}}
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="id_brand" class="form-label">Marca</label>
            <select id="id_brand" name="id_brand" class="selectpicker form-control" data-live-search="true" required>
              <option value="">Seleccione una marca</option>
              @foreach($brands as $brand)
                <option value="{{ $brand->id_brand }}">{{ $brand->name_brand }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label for="id_model" class="form-label">Modelo</label>
            <select id="id_model" name="id_model" class="selectpicker form-control" data-live-search="true" required>
              <option value="">Seleccione un modelo</option>
              @foreach($models as $model)
                <option value="{{ $model->id_model }}">{{ $model->name_model }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Servicio --}}
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

        {{-- Lavado --}}
        <div class="form-check mb-4">
          <input type="checkbox" id="wash_service" name="wash_service" class="form-check-input">
          <label for="wash_service" class="form-check-label">Incluye Servicio de Lavado</label>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="reset" class="btn btn-secondary me-md-2"><i class="fas fa-eraser me-1"></i> Limpiar</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Guardar</button>
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

  const searchUrl = '{{ route("estacionamiento.search") }}';
  let debounceTimer;

  const plateInput = $('#plate');
  const plateFeedback = $('#plateFeedback');
  const plateLoading = $('#plateLoading');
  const nameInput = $('#name');
  const phoneInput = $('#phone');
  const brandSelect = $('#id_brand');
  const modelSelect = $('#id_model');

  function showFeedback(element, message, isValid) {
    const input = $(`#${element}`);
    const feedback = $(`#${element}Feedback`);
    input.removeClass('is-invalid is-valid');
    feedback.text('');
    if (message) {
      input.addClass(isValid ? 'is-valid' : 'is-invalid');
      if (!isValid) feedback.text(message);
    }
  }

  function searchByPlate(plate) {
    if (!plate) return;
    plateLoading.show();

    $.ajax({
      url: searchUrl,
      method: 'GET',
      dataType: 'json',
      data: { plate },
      success: function(response) {
        if (response.found) {
          nameInput.val(response.name || '');
          phoneInput.val(response.phone || '');
          if (response.id_brand) brandSelect.val(response.id_brand).selectpicker('refresh');
          if (response.id_model) modelSelect.val(response.id_model).selectpicker('refresh');
          showFeedback('plate', 'Datos encontrados', true);
        } else {
          showFeedback('plate', 'Patente no encontrada, puede ingresar manualmente', false);
        }
      },
      error: function(xhr) {
        showFeedback('plate', 'Error buscando patente, puede ingresar manualmente', false);
        console.error('Error:', xhr.responseText);
      },
      complete: function() {
        plateLoading.hide();
      }
    });
  }

  plateInput.on('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      searchByPlate($(this).val().trim());
      return false;
    }
  }).on('input', function() {
    const plate = $(this).val().trim().toUpperCase();
    $(this).val(plate);
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => searchByPlate(plate), 500);
  }).on('blur', function() {
    searchByPlate($(this).val().trim());
  });

  const today = new Date().toISOString().slice(0, 10);
  $('#start_date').attr('min', today);
  $('#start_date').on('change', function() {
    $('#end_date').attr('min', $(this).val());
  });
});
</script>
@endpush
