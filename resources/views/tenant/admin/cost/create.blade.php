@extends('tenant.layouts.admin')

@section('title', 'Registrar Costo de Servicio Básico')
@section('page_title', 'Registrar Costo de Servicio Básico')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
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
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
        <i class="fas fa-file-invoice-dollar mr-2"></i>
      <h5 class="mb-0">Registrar Costo de Servicio Básico</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('cost_basic_service.store') }}" method="POST" autocomplete="off" id="form-register-cost">
        @csrf

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="branch_office_id">Sucursal</label>
            <select name="branch_office_id" id="branch_office_id" class="selectpicker form-control @error('branch_office_id') is-invalid @enderror" data-live-search="true" required>
                <option value="">—</option>
                @foreach($sucursales as $sucursal)
                <option value="{{ $sucursal->id_branch }}" {{ old('branch_office_id') == $sucursal->id_branch ? 'selected' : '' }}>
                  {{ $sucursal->name_branch_offices }}
                </option>
              @endforeach
            </select>
            @error('branch_office_id')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="name">Nombre del Servicio</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"value="{{ old('name') }}" required>
            @error('name')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-12 col-md-6">
            <label for="value">Valor</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input type="number" name="value" id="value" class="form-control @error('value') is-invalid @enderror" step="0.01" min="0" value="{{ old('value') }}" required>
            </div>
            @error('value')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-12 col-md-6">
            <label for="date">Fecha</label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
            @error('date')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="note">Nota</label>
          <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="2">{{ old('note') }}</textarea>
          @error('note')
            <span class="invalid-feedback d-block">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <button type="reset" class="btn btn-secondary">
              <i class="mr-1"></i> Cancelar
            </button>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary" id="submit-btn">
              <i class="mr-1"></i> Guardar
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
  $('.selectpicker').selectpicker();

  $('#form-register-cost').on('submit', function(e) {
    e.preventDefault();
    let $form = $(this);
    let $btn = $('#submit-btn');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');
    $.ajax({
      url: $form.attr('action'),
      type: "POST",
      data: $form.serialize(),
      success: function(response) {
        $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar');
        if(response.success){
          Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: response.message,
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            window.location.href = "{{ route('cost_basic_service.show') }}";
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.message || 'Error al registrar el costo.'
          });
        }
      },
      error: function(xhr) {
        $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Guardar');
        let msg = 'Error al registrar el costo.';
        if(xhr.responseJSON && xhr.responseJSON.message){
          msg = xhr.responseJSON.message;
        } else if(xhr.responseJSON && xhr.responseJSON.errors){
          let errors = xhr.responseJSON.errors;
          msg = Object.values(errors).join(', ');
        }
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: msg
        });
      }
    });
  });
});
</script>
@endpush