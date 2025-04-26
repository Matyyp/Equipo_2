@extends('tenant.layouts.admin')

@section('title', 'Editar Sucursal')
@section('page_title', 'Editar Sucursal')

@section('content')
<div class="card">
    <div class="card-body">
        {{-- Mensajes de error --}}
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
        <form action="{{ route('sucursales.update', $branch->id_branch) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>Horario</label>
                <input type="text" name="schedule" class="form-control" value="{{ old('schedule', $branch->schedule) }}" required>
            </div>

            <div class="form-group mb-3">
                <label>Calle</label>
                <input type="text" name="street" class="form-control" value="{{ old('street', $branch->street) }}" required>
            </div>

            <div class="form-group mb-3">
                <label>Región</label>
                <select id="region-select" class="form-select">
                    <option value="">Seleccione región</option>
                    @foreach ($locacion->pluck('region')->unique() as $region)
                        <option value="{{ $region }}"
                            {{ $branch->branch_office_location?->region == $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Comuna</label>
                <select name="id_location" id="commune-select" class="form-select" required>
                    <option value="">Seleccione comuna</option>
                    @foreach ($locacion as $loc)
                        <option value="{{ $loc->id_location }}"
                                data-region="{{ $loc->region }}"
                                {{ $branch->id_location == $loc->id_location ? 'selected' : '' }}>
                            {{ $loc->commune }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Negocio</label>
                <select name="id_business" class="form-select" required>
                    <option value="">Seleccione negocio</option>
                    @foreach ($business as $b)
                        <option value="{{ $b->id_business }}"
                            {{ $branch->id_business == $b->id_business ? 'selected' : '' }}>
                            {{ $b->name_business }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar
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

        const updateComunas = () => {
            const selectedRegion = regionSelect.value;
            Array.from(communeSelect.options).forEach(option => {
                if (!option.value) return;
                option.hidden = option.dataset.region !== selectedRegion;
            });

            // Si la comuna seleccionada no pertenece a la región seleccionada, reset
            if (communeSelect.selectedOptions.length && communeSelect.selectedOptions[0].hidden) {
                communeSelect.value = '';
            }
        };

        updateComunas(); // Al cargar

        regionSelect.addEventListener('change', updateComunas);
    });
</script>
@endpush
