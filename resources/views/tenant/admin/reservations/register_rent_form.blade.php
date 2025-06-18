@extends('tenant.layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div>
        <i class="fas fa-car mr-2"></i> Registrar Arriendo
      </div>
    </div>

    <div class="card-body">
      <form action="{{ route('reservas.guardarRegistroRenta', $reservation) }}" method="POST" autocomplete="off">
        @csrf
        
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="form-row">
          <!-- Columna izquierda -->
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label for="name">Nombre Cliente</label>
              <input type="text" id="name" class="form-control" value="{{ $reservation->user->name }}" readonly>
            </div>

            <div class="form-group">
              <label for="email">Correo</label>
              <input type="text" id="email" class="form-control" value="{{ $reservation->user->email }}" readonly>
            </div>

            <div class="form-group">
              <label for="rut">RUT</label>
              <input type="text" id="rut" class="form-control" value="{{ $reservation->rut }}" readonly>
            </div>

            <input type="hidden" name="return_in" value="{{ (int) $reservation->branchOffice->id_branch_offices }}">

            <div class="form-group">
              <label for="branch">Retorno en Sucursal</label>
              <input type="text" id="branch" class="form-control" value="{{ $reservation->branchOffice->name_branch_offices }}" readonly>
            </div>

            <div class="form-group">
              <label for="address">Dirección</label>
              <input type="text" name="address" id="address" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="driving_license">Licencia de Conducir</label>
              <input type="text" name="driving_license" id="driving_license" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="class_licence">Clase de Licencia</label>
              <input type="text" name="class_licence" id="class_licence" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="expire">Fecha de Expiración</label>
              <input type="date" name="expire" id="expire" class="form-control" required>
            </div>
          </div>

          <!-- Columna derecha -->
          <div class="col-12 col-md-6">
            <div class="form-group">
              <label for="guarantee">Garantía</label>
              <div class="input-group">
                <input type="number" name="guarantee" id="guarantee" class="form-control" required>
                <span class="input-group-text">
                  <i class="fas fa-dollar-sign"></i>
                </span>
              </div>
            </div>

            <div class="form-group">
              <label for="payment">Pago</label>
              <input type="text" id="payment" class="form-control" value="Reserva cancelada por: ${{ number_format($reservation->reservationPayment->amount ?? 0, 0, ',', '.') }}" readonly>
            </div>

            <div class="form-group">
              <label for="departure_fuel">Combustible de Salida</label>
              <select name="departure_fuel" id="departure_fuel" class="form-control" required>
                <option value="">—</option>
                @foreach(['vacío','1/4','1/2','3/4','lleno'] as $level)
                  <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="km_exit">Kilómetros de Salida</label>
              <div class="input-group">
                <input type="number" name="km_exit" id="km_exit" class="form-control" required>
                <span class="input-group-text">
                  <i class="fas fa-tachometer-alt"></i>
                </span>
              </div>
            </div>

            <div class="form-group">
              <label for="observation">Observación</label>
              <textarea name="observation" id="observation" class="form-control" rows="3" maxlength="500"></textarea>
              <small class="text-muted">Máximo 500 caracteres</small>
            </div>

            <div class="form-group">
              <label for="start_date">Fecha Inicio</label>
              <input type="date" id="start_date" class="form-control" value="{{ $reservation->start_date }}" readonly>
            </div>

            <div class="form-group">
              <label for="end_date">Fecha Término</label>
              <input type="date" id="end_date" class="form-control" value="{{ $reservation->end_date }}" readonly>
            </div>
          </div>
        </div>

        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-2"></i> Guardar Registro
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection