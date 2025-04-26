<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Contrato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Contrato</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('contratos.store') }}" method="POST">
        @csrf

        {{-- Contacto (múltiple checkbox) --}}
        <div class="mb-3">
            <label><strong>Datos de contacto:</strong></label>
            @foreach ($contactInformations as $contact)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="contact_information_ids[]" value="{{ $contact->id }}" id="contact{{ $contact->id }}">
                    <label class="form-check-label" for="contact{{ $contact->id }}">
                        {{ $contact->type_contact }} - {{ $contact->data_contact }}
                    </label>
                </div>
            @endforeach
        </div>

        {{-- Reglas (múltiple checkbox) --}}
        <div class="mb-3">
            <label><strong>Reglas:</strong></label>
            @foreach ($rules as $rule)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="rule_ids[]" value="{{ $rule->id }}" id="rule{{ $rule->id }}">
                    <label class="form-check-label" for="rule{{ $rule->id }}">
                        {{ $rule->name }}
                    </label>
                </div>
            @endforeach
        </div>

        {{-- Tipo de contrato (radio) --}}
        <div class="mb-3">
            <label><strong>Tipo de contrato:</strong></label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="contract_type" id="rent" value="rent" checked onclick="toggleParkingOptions()">
                <label class="form-check-label" for="rent">Arriendo</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="contract_type" id="parking" value="parking" onclick="toggleParkingOptions()">
                <label class="form-check-label" for="parking">Estacionamiento</label>
            </div>
        </div>

        {{-- Subtipo solo si elige parking --}}
        <div class="mb-3" id="parking-type-section" style="display: none;">
            <label><strong>Subtipo de Estacionamiento:</strong></label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="parking_type" value="annual" id="parkingAnnual">
                <label class="form-check-label" for="parkingAnnual">Mensual</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="parking_type" value="daily" id="parkingDaily">
                <label class="form-check-label" for="parkingDaily">Diario</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('contratos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

<script>
    function toggleParkingOptions() {
        const parkingTypeSection = document.getElementById('parking-type-section');
        const isParking = document.getElementById('parking').checked;
        parkingTypeSection.style.display = isParking ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', toggleParkingOptions);
</script>
</body>
</html>
