@extends('tenant.layouts.admin')

@section('title', 'Registrar Auto')
@section('page_title', 'Registrar Auto')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('autos.store') }}" method="POST">
            @csrf

            {{-- Patente --}}
            <div class="form-group mb-3">
                <label for="patent">Patente</label>
                <input type="text" name="patent" class="form-control" value="{{ old('patent') }}" required>
            </div>

            {{-- Marca --}}
            <div class="form-group mb-3">
                <label for="brand_id">Marca</label>
                <select name="brand_id" class="form-select select2" required>
                    <option value="">Seleccione una marca</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id_brand }}">{{ $brand->name_brand }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Modelo --}}
            <div class="form-group mb-3">
                <label for="model_id">Modelo</label>
                <select name="model_id" class="form-select select2" required>
                    <option value="">Seleccione un modelo</option>
                    @foreach($models as $model)
                        <option value="{{ $model->id_model }}">{{ $model->name_model }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Arriendo --}}
            <div class="form-group mb-3">
                <label for="is_rentable">¿Está disponible para arriendo?</label>
                <select name="is_rentable" id="is_rentable" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="1" {{ old('is_rentable') == 1 ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ old('is_rentable') == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>

            {{-- Valor Arriendo --}}
            <div class="form-group mb-3 d-none" id="rent_value_group">
                <label for="value_rent">Valor Arriendo</label>
                <input type="number" name="value_rent" id="value_rent" class="form-control" min="0">
            </div>

            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('autos.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#is_rentable').on('change', function () {
            if ($(this).val() == "1") {
                $('#rent_value_group').removeClass('d-none');
                $('#value_rent').attr('required', true);
            } else {
                $('#rent_value_group').addClass('d-none');
                $('#value_rent').val('').removeAttr('required');
            }
        }).trigger('change');
    });
</script>
@endpush
