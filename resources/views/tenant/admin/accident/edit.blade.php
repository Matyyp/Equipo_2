@extends('tenant.layouts.admin')

@section('title','Editar Accidente')
@section('page_title','Editar Accidente')

@section('content')
<style>
.accident-photo-wrap {
    position: relative;
    display: inline-block;
    margin: 8px;
}
.accident-photo-wrap img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 1.5px solid #888;
    border-radius: 4px;
}
.accident-photo-delete {
    position: absolute;
    top: 5px;
    right: 5px;
    z-index: 2;
}
.accident-photo-delete form {
    margin: 0;
}
.accident-photo-delete button {
    padding: 2px 6px;
    border-radius: 50%;
    font-size: 18px;
    line-height: 1;
    min-width: 28px;
    min-height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #dc3545;
    border: none;
    color: white;
    opacity: 0.8;
    cursor: pointer;
}
.accident-photo-delete button:hover {
    opacity: 1;
}
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-car-crash"></i> Editar Registro de Accidente
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

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulario para editar accidente --}}
            <form action="{{ route('accidente.update', $accidente->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Número de arriendo asociado solo visual, no editable --}}
                <div class="mb-3">
                    <label for="id_rent"><b>Número de arriendo</b></label>
                    <input type="text" class="form-control form-control-sm"
                        value="#{{ $accidente->id_rent }}{{ $accidente->rent ? ' - ' . ($accidente->rent->client_name ?? 'Sin cliente') . ' (' . ($accidente->rent->start_date ?? '') . ' / ' . ($accidente->rent->end_date ?? '') . ')' : '' }}"
                        disabled>
                </div>

                {{-- Vehículo asociado solo visual, no editable --}}
                @if(isset($rentalCar))
                    <div class="mb-3">
                        <label for="rental_car_id"><b>Vehículo Asociado</b></label>
                        <input type="hidden" name="rental_car_id" value="{{ $rentalCar->id }}">
                        <input type="text" class="form-control"
                            value="Marca: {{ $rentalCar->brand->name_brand ?? '' }} | Modelo: {{ $rentalCar->model->name_model ?? '' }}"
                            disabled>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="status">Estado</label>
                    <input type="text" class="form-control" value="{{ $accidente->status === 'in progress' ? 'En progreso' : 'Completado' }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="name_accident">Nombre Accidente</label>
                    <input type="text" class="form-control" name="name_accident" value="{{ old('name_accident', $accidente->name_accident) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" name="description">{{ old('description', $accidente->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="bill_number">N° Factura</label>
                    <input type="text" class="form-control" name="bill_number" value="{{ old('bill_number', $accidente->bill_number) }}">
                </div>
                <div class="mb-3">
                    <label for="description_accident_term">Descripción término</label>
                    <textarea class="form-control" name="description_accident_term">{{ old('description_accident_term', $accidente->description_accident_term) }}</textarea>
                </div>

                {{-- Agregar nuevas fotos --}}
                <div class="mb-3">
                    <label for="photos">Agregar nuevas fotos (opcional)</label>
                    <input type="file" class="form-control" name="photos[]" accept="image/*" multiple>
                </div>

                

                <div class="form-group row justify-content-end">
                    <div class="col-auto">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-1">
                            Cancelar
                        </a>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-info" id="submit-btn">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>

            {{-- Fotos actuales, fuera del form de editar accidente --}}
            <div class="mb-3 mt-1">
                <label for="photos"><b>Foto(s) actuales</b></label>
                <div class="d-flex flex-wrap align-items-center">
                    @foreach($photos as $photo)
                        <div class="accident-photo-wrap">
                            <img src="{{ tenant_asset($photo->photo) }}" alt="Foto accidente">
                            <div class="accident-photo-delete">
                                <form action="{{ route('accidente.photo.destroy', ['accidente' => $accidente->id, 'photo' => $photo->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        title="Eliminar foto"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta foto?')">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection