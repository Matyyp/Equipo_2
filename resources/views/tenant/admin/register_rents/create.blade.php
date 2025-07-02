@extends('tenant.layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-car mr-2"></i> Registrar arriendo de vehículo 
      </div>
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

      <form action="{{ route('registro-renta.store') }}" method="POST" autocomplete="off">
      @csrf

      <!-- === DATOS DEL VEHÍCULO === -->
      <h5 class="mt-3 border-bottom pb-2 text-info"><i class="fas fa-car"></i> Datos del Vehículo</h5>
      <div class="row">
        <div class="col-md-6">
          <label class="form-label text-dark fw-bold">Vehículo</label>
          <select name="id_car" id="id_car" class="form-control" required>
            <option value="">Seleccione un vehículo</option>
            @foreach ($cars as $car)
              <option value="{{ $car->id }}" data-price="{{ $car->price_per_day }}">
                {{ $car->brand->name_brand }} {{ $car->model->name_model }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label text-dark fw-bold">Precio por día</label>
          <input type="text" id="price_per_day" class="form-control bg-light" readonly>
        </div>
      </div>

      <!-- === DATOS DEL CLIENTE === -->
      <h5 class="mt-4 border-bottom pb-2 text-info"><i class="fas fa-user"></i> Datos del Cliente</h5>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="name">Nombre Cliente</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Nombre completo del cliente" required>
          </div>
          <div class="form-group">
            <label for="rut">RUT</label>
            <input type="text" name="rut" id="rut" class="form-control" placeholder="Ej: 12.345.678-9" required>
          </div>
          <div class="form-group">
            <label for="email">Correo</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="ejemplo@ejemplo.com" required>
          </div>
          <div class="form-group">
            <label for="number_phone">Teléfono</label>
            <input type="text" name="number_phone" id="number_phone" class="form-control" placeholder="Ej: +56 9 1234 5678">
          </div>
          
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="address">Dirección</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Calle falsa 123" required>
          </div>
          <div class="form-group">
            <label for="driving_license">Licencia de Conducir</label>
            <input type="text" name="driving_license" id="driving_license" class="form-control" placeholder="Código licencia conducir" required>
          </div>
          <div class="form-group">
            <label for="class_licence">Clase de Licencia</label>
            <input type="text" name="class_licence" id="class_licence" class="form-control" placeholder="A,B,C,etc" required>
          </div>
          <div class="form-group">
            <label for="expire">Fecha de Expiración</label>
            <input type="date" name="expire" id="expire" class="form-control" required>
          </div>
        </div>
      </div>

      <!-- === DETALLES DEL ARRIENDO === -->
      <h5 class="mt-4 border-bottom pb-2 text-info"><i class="fas fa-file-contract"></i> Detalles del Arriendo</h5>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="guarantee">Garantía</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
              <input type="number" name="guarantee" id="guarantee" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label for="departure_fuel">Combustible de Salida</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-gas-pump"></i></span>
              <select name="departure_fuel" id="departure_fuel" class="form-control" required>
                <option value="">—</option>
                @foreach(['vacío','1/4','1/2','3/4','lleno'] as $level)
                  <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="km_exit">Kilómetros de Salida</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
              <input type="number" name="km_exit" id="km_exit" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label for="observation">Observación</label>
            <textarea name="observation" id="observation" class="form-control" rows="3" maxlength="500"></textarea>
            <small class="text-muted">Máximo 500 caracteres</small>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="start_date">Fecha Inicio</label>
            <input type="text" id="start_date" name="start_date" class="form-control" placeholder="dd/mm/aaaa" required readonly>
          </div>
          <div class="form-group">
            <label for="end_date">Fecha Término</label>
            <input type="text" id="end_date" name="end_date" class="form-control" placeholder="dd/mm/aaaa" required readonly>
          </div>
          <div class="form-group">
            <label class="form-label text-dark fw-bold">Total</label>
            <input type="text" id="total_price" class="form-control bg-light" readonly>
          </div>
        </div>
      </div>

      <!-- === BOTONES === -->
      <div class="form-group row justify-content-end mt-4">
        <div class="col-auto">
          <a href="{{ route('registro-renta.index') }}" class="btn btn-secondary me-1">Cancelar</a>
          <button type="button" id="confirmar-btn" class="btn btn-primary">Registrar</button>
        </div>
      </div>
    </form>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // RUT Formatter
  document.addEventListener('DOMContentLoaded', function () {
    const rutInput = document.getElementById('rut');
    function formatRut(value) {
      value = value.replace(/^0+/, '').replace(/[^\dkK]+/g, '').toUpperCase();
      if (value.length <= 1) return value;
      let cuerpo = value.slice(0, -1);
      let dv = value.slice(-1);
      return cuerpo + '-' + dv;
    }
    rutInput.addEventListener('input', function () {
      const raw = this.value.replace(/[^\dkK]/gi, '');
      if (raw.length > 1) {
        this.value = formatRut(raw);
      }
    });
  });
</script>

<script>
  // Cargar cliente si existe por correo
  document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.querySelector('input[name="email"]');
    const nameInput = document.querySelector('input[name="name"]');

    emailInput.addEventListener('blur', function () {
      const email = emailInput.value.trim();

      if (email.length > 0) {
        fetch(`/buscar-cliente?email=${encodeURIComponent(email)}`)
          .then(response => response.json())
          .then(data => {
            if (data.found) {
              nameInput.value = data.name;
              nameInput.readOnly = true;
              nameInput.classList.add('bg-light');
            } else {
              // Solo limpiar si estaba en modo readonly (autocompletado)
              if (nameInput.readOnly) {
                nameInput.value = '';
              }
              nameInput.readOnly = false;
              nameInput.classList.remove('bg-light');
            }
          })
          .catch(error => {
            console.error('Error al buscar cliente:', error);
          });
      }
    });
  });
</script>

<script>
  let fechasOcupadas = [];
  const startInput = document.getElementById('start_date');
  const endInput = document.getElementById('end_date');

  const startPicker = flatpickr(startInput, {
    dateFormat: "Y-m-d",
    disable: [],
    onChange: function(selectedDates, dateStr) {
      endPicker.set("minDate", dateStr);
      calcularTotal();
    }
  });

  const endPicker = flatpickr(endInput, {
    dateFormat: "Y-m-d",
    disable: [],
    onChange: function() {
      calcularTotal();
    }
  });

  document.getElementById('id_car').addEventListener('change', function () {
    const carId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    document.getElementById('price_per_day').value = price ? `$${parseInt(price).toLocaleString('es-CL')}` : '';
    calcularTotal();

    if (!carId) return;
    startPicker.clear();
    endPicker.clear();

    fetch(`/registro-renta/fechas-ocupadas/${carId}`)
      .then(response => response.json())
      .then(data => {
        fechasOcupadas = data;
        startPicker.set("disable", fechasOcupadas);
        endPicker.set("disable", fechasOcupadas);

        const today = new Date().toISOString().split('T')[0];
        startPicker.set("minDate", today);
        endPicker.set("minDate", today);
      })
      .catch(error => {
        console.error("Error obteniendo fechas ocupadas:", error);
      });
  });

  function calcularTotal() {
    const price = parseInt(document.querySelector('#id_car option:checked')?.getAttribute('data-price')) || 0;
    const start = startInput.value;
    const end = endInput.value;

    if (price > 0 && start && end) {
      const startDate = new Date(start);
      const endDate = new Date(end);
      const diffTime = endDate - startDate;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
      if (diffDays > 0) {
        const total = price * diffDays;
        document.getElementById('total_price').value = `$${total.toLocaleString('es-CL')}`;
      } else {
        document.getElementById('total_price').value = '';
      }
    } else {
      document.getElementById('total_price').value = '';
    }
  }
</script>

<script>
  document.getElementById('confirmar-btn').addEventListener('click', function (e) {
    Swal.fire({
      title: '¿Confirmar Registro de Arriendo?',
      html: 'Los datos ingresados se usarán en el contrato y <b>no podrán modificarse</b> posteriormente.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, registrar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        document.querySelector('form').submit();
      }
    });
  });
</script>
@endpush

@endsection