@extends('tenant.layouts.admin')

@section('title', 'Crear Sucursal')
@section('page_title', 'Registrar Nueva Sucursal')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Validación de errores --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('sucursales.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label>Horario</label>
                <input type="text" name="schedule" class="form-control" value="{{ old('schedule') }}" required>
            </div>

            <div class="form-group mb-3">
                <label>Calle</label>
                <input type="text" name="street" class="form-control" value="{{ old('street') }}" required>
            </div>

            <div class="form-group mb-3">
                <label>Región</label>
                <select id="region-select" class="form-select" required>
                    <option value="">Seleccione región</option>
                    @foreach ($locacion->pluck('region')->unique() as $region)
                        <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Comuna</label>
                <select name="id_location" id="commune-select" class="form-select" required>
                    <option value="">Seleccione comuna</option>
                    @foreach ($locacion as $loc)
                        <option value="{{ $loc->id_location }}" data-region="{{ $loc->region }}">
                            {{ $loc->commune }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label>Negocio</label>
                <select name="id_business" class="form-select" required>
                    <option value="">Seleccione negocio</option>
                    @foreach ($business as $b)
                        <option value="{{ $b->id_business }}">{{ $b->name_business }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const regionSelect = document.getElementById('region-select');
        const communeSelect = document.getElementById('commune-select');

        regionSelect.addEventListener('change', () => {
            const selectedRegion = regionSelect.value;
            Array.from(communeSelect.options).forEach(option => {
                if (!option.value) return;
                option.hidden = option.dataset.region !== selectedRegion;
            });

            communeSelect.value = '';
        });

        // Al cargar la vista, ocultar comunas que no coincidan si ya hay una región preseleccionada
        if (regionSelect.value) {
            const selectedRegion = regionSelect.value;
            Array.from(communeSelect.options).forEach(option => {
                if (!option.value) return;
                option.hidden = option.dataset.region !== selectedRegion;
            });
        }
    });
</script>
@endpush
