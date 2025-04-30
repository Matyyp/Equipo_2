@extends('tenant.layouts.admin')

@section('title', 'Crear Contrato')
@section('page_title', 'Nuevo Contrato')

@section('content')
<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contratos.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Tipo de Contrato</label>
                <select name="contract_type" id="contract_type" class="form-control" required>
                    <option value="">Seleccione</option>
                    @if(!$hasRent)
                    <option value="rent">Renta</option>
                    @endif
                    @if(!$hasAnnual || !$hasDaily)
                    <option value="parking">Estacionamiento</option>
                    @endif
                </select>
            </div>

            <div class="form-group" id="parking_type_group" style="display:none;">
                <label>Tipo de Estacionamiento</label>
                <select name="parking_type" id="parking_type" class="form-control">
                    <option value="">Seleccione</option>
                    @if(!$hasAnnual)
                    <option value="annual">Anual</option>
                    @endif
                    @if(!$hasDaily)
                    <option value="daily">Diario</option>
                    @endif
                </select>
            </div>

            {{-- Aquí estarán los datos solo si es anual --}}
            <div id="annual_fields" style="display:none;">
                <div class="form-group">
                    <label for="important_note">Nota Importante</label>
                    <input type="text" name="important_note" id="important_note" class="form-control" value="{{ old('important_note') }}">
                </div>

                <div class="form-group">
                    <label for="expiration_date">Fecha de Expiración</label>
                    <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{ old('expiration_date') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Datos de Contacto</label>
                @foreach($contactInformation as $contact)
                <div class="form-check">
                    <input type="checkbox" name="contact_information[]" value="{{ $contact->id_contact_information }}" class="form-check-input">
                    <label class="form-check-label">{{ $contact->type_contact }}: {{ $contact->data_contact }}</label>
                </div>
                @endforeach
            </div>

            <div class="form-group">
                <label>Reglas</label>
                @foreach($rules as $rule)
                <div class="form-check">
                    <input type="checkbox" name="rules[]" value="{{ $rule->id_rule }}" class="form-check-input">
                    <label class="form-check-label">{{ $rule->description }}</label>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('contratos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contractType = document.getElementById('contract_type');
        const parkingType = document.getElementById('parking_type');
        const parkingTypeGroup = document.getElementById('parking_type_group');
        const annualFields = document.getElementById('annual_fields');

        function toggleFields() {
            if (contractType.value === 'parking') {
                parkingTypeGroup.style.display = 'block';
                parkingType.required = true;
                toggleAnnualFields(); 
            } else {
                parkingTypeGroup.style.display = 'none';
                annualFields.style.display = 'none';
                parkingType.value = '';
                parkingType.required = false;
            }
        }

        function toggleAnnualFields() {
            if (parkingType.value === 'annual') {
                annualFields.style.display = 'block';
            } else {
                annualFields.style.display = 'none';
            }
        }

        contractType.addEventListener('change', toggleFields);
        parkingType.addEventListener('change', toggleAnnualFields);

        toggleFields();
        toggleAnnualFields();
    });
</script>
@endpush
