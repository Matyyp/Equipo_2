@extends('tenant.layouts.admin')

@section('title', 'Editar Contrato')
@section('page_title', 'Editar Contrato')

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

        <form action="{{ route('contratos.update', $contract->id_contract) }}" method="POST">
            @csrf
            @method('PUT')

            @if($contractTypeParking === 'annual')
                <div class="form-group">
                    <label for="important_note">Nota Importante</label>
                    <input type="text" name="important_note" id="important_note" class="form-control"
                        value="{{ old('important_note', $contract->contract_contract_parking->contract_parking_contract_annual->important_note ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="expiration_date">Fecha de Expiración</label>
                    <input type="date" name="expiration_date" id="expiration_date" class="form-control"
                        value="{{ old('expiration_date', $contract->contract_contract_parking->contract_parking_contract_annual?->expiration_date 
                            ? \Carbon\Carbon::parse($contract->contract_contract_parking->contract_parking_contract_annual->expiration_date)->format('Y-m-d') 
                            : '') }}">
                </div>
            @endif

            <div class="form-group">
                <label>Datos de Contacto</label>
                @foreach($contactInformation as $contact)
                <div class="form-check">
                    <input type="checkbox" name="contact_information[]" value="{{ $contact->id_contact_information }}" class="form-check-input"
                        {{ in_array($contact->id_contact_information, $contactIds) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $contact->type_contact }}: {{ $contact->data_contact }}</label>
                </div>
                @endforeach
            </div>

            <div class="form-group">
                <label>Reglas</label>
                @foreach($rules as $rule)
                <div class="form-check">
                    <input type="checkbox" name="rules[]" value="{{ $rule->id_rule }}" class="form-check-input"
                        {{ in_array($rule->id_rule, $ruleIds) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $rule->description }}</label>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('contratos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
