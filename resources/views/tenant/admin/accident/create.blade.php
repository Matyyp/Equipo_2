@extends('tenant.layouts.admin')

@section('title','Registrar Siniestro')
@section('page_title','Nuevo Siniestro')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-car-crash"></i> Nuevo Registro de Siniestro
        </div>
        <div class="card-body">
            {{-- Mensajes de error --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mostrar mensaje si no hay arriendos --}}
            @if((isset($registerRents) && $registerRents->isEmpty()) && (!isset($selectedRent) || !$selectedRent))
                <div class="alert alert-warning text-center">
                    <b>No existen arriendos registrados.</b><br>
                    Debe registrar un arriendo antes de poder crear un siniestro.
                </div>
            @else
            <form action="{{ route('accidente.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Número de arriendo --}}
                @if(isset($selectedRent) && $selectedRent)
                    <div class="mb-3">
                        <label for="id_rent"><b>N° de Arriendo</b></label>
                        <input type="text" class="form-control" value="#{{ $selectedRent->id }} - {{ $selectedRent->client_name }}" disabled>
                        <input type="hidden" name="id_rent" value="{{ $selectedRent->id }}">
                    </div>
                @else
                    <div class="mb-3">
                        <label for="id_rent"><b>Número de arriendo</b></label>
                        <select class="form-control" name="id_rent" required>
                            <option value="">Seleccione un número de arriendo</option>
                            @foreach($registerRents as $rent)
                                <option value="{{ $rent->id }}" {{ old('id_rent') == $rent->id ? 'selected' : '' }}>
                                    #{{ $rent->id }} - {{ $rent->client_name ?? 'Sin cliente' }}
                                    ({{ $rent->start_date ?? '' }} / {{ $rent->end_date ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Vehículo asociado solo visual, no editable --}}
                @if(isset($selectedRent) && $selectedRent)
                    <div class="mb-3">
                        <label for="rental_car_id"><b>Vehículo Asociado</b></label>
                        <input type="hidden" name="rental_car_id" value="{{ $selectedRent->rentalCar->id }}">
                        <input type="text" class="form-control"
                            value="Marca: {{ $selectedRent->rentalCar->brand->name_brand ?? '' }} | Modelo: {{ $selectedRent->rentalCar->model->name_model ?? '' }}"
                            disabled>
                    </div>
                @elseif(isset($rentalCar) && $rentalCar)
                    <div class="mb-3">
                        <label for="rental_car_id"><b>Vehículo Asociado</b></label>
                        <input type="hidden" name="rental_car_id" value="{{ $rentalCar->id }}">
                        <input type="text" class="form-control"
                            value="Marca: {{ $rentalCar->brand->name_brand ?? '' }} | Modelo: {{ $rentalCar->model->name_model ?? '' }}"
                            disabled>
                    </div>
                @endif

                {{-- ... resto del formulario igual ... --}}
                <div class="mb-3">
                    <label for="name_accident">Nombre Siniestro</label>
                    <input type="text" class="form-control" name="name_accident" value="{{ old('name_accident') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="bill_number">N° Factura</label>
                    <input type="text" class="form-control" name="bill_number" value="{{ old('bill_number') }}">
                </div>
                <div class="mb-3">
                    <label for="description_accident_term">Descripción término</label>
                    <textarea class="form-control" name="description_accident_term">{{ old('description_accident_term') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="photos">Fotos (puedes seleccionar varias)</label>
                    <input type="file" class="form-control" name="photos[]" accept="image/*" multiple>
                    <small class="form-text text-muted">Puedes seleccionar varias imágenes manteniendo presionada la tecla Ctrl o Shift.</small>
                </div>
                <div class="mb-3">
                    <label for="status">Estado</label>
                    <input type="text" class="form-control" value="En progreso" disabled>
                    <small class="form-text text-muted">El siniestro se crea siempre como "En progreso".</small>
                </div>

                <div class="form-group row justify-content-end">
                    <div class="col-auto">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-1">
                            Cancelar
                        </a>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection