@extends('tenant.layouts.admin')

@section('content')
<div class="container">
    <h2>Registrar Arriendo</h2>
    <form action="{{ route('reservas.guardarRegistroRenta', $reservation) }}" method="POST">
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

        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nombre Cliente</label>
                    <input type="text" class="form-control" value="{{ $reservation->user->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Correo</label>
                    <input type="text" class="form-control" value="{{ $reservation->user->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label>RUT</label>
                    <input type="text" class="form-control" value="{{ $reservation->rut }}" readonly>
                </div>

                <input type="hidden" name="return_in" value="{{ (int) $reservation->branchOffice->id_branch_offices }}">

                <div class="mb-3">
                    <label>Retorno en Sucursal</label>
                    <input type="text" class="form-control" value="{{ $reservation->branchOffice->name_branch_offices }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Dirección</label>
                    <input type="text" name="address" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Licencia de Conducir</label>
                    <input type="text" name="driving_license" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Clase de Licencia</label>
                    <input type="text" name="class_licence" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Fecha de Expiración</label>
                    <input type="date" name="expire" class="form-control" required>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Garantía</label>
                    <input type="number" name="guarantee" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Pago</label>
                    <input type="text" class="form-control" value="Reserva cancelada por: ${{ number_format($reservation->reservationPayment->amount ?? 0, 0, ',', '.') }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Combustible de Salida</label>
                    <select name="departure_fuel" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach(['vacío','1/4','1/2','3/4','lleno'] as $level)
                            <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Kilómetros de Salida</label>
                    <input type="number" name="km_exit" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Observación</label>
                    <textarea name="observation" class="form-control" rows="3" maxlength="500"></textarea>
                </div>

                <div class="mb-3">
                    <label>Fecha Inicio</label>
                    <input type="date" class="form-control" value="{{ $reservation->start_date }}" readonly>
                </div>

                <div class="mb-3">
                    <label>Fecha Término</label>
                    <input type="date" class="form-control" value="{{ $reservation->end_date }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Guardar Registro</button>
            </div>
        </div>
    </form>
</div>
@endsection