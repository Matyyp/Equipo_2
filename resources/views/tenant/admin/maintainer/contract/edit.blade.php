@extends('tenant.layouts.admin')

@section('title', 'Editar Contrato')
@section('page_title', 'Editar Contrato')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Sucursal: <strong>{{ $branchName ?? 'N/D' }}</strong></h5>
        <h6 class="mb-4">Tipo de Contrato: 
            <span class="badge bg-info text-dark">
                {{ strtoupper(str_replace('_', ' ', $type)) }}
            </span>
        </h6>

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

            @if($type === 'parking_annual')
                <div class="form-group">
                    <label for="important_note">Nota Importante</label>
                    <input type="text" name="important_note" id="important_note" class="form-control"
                        value="{{ old('important_note', $contract->contract_contract_parking->contract_parking_contract_annual->important_note ?? '') }}">
                </div>

                <div class="form-group">
                    <label for="expiration_date">Fecha de Expiración</label>
                    <input type="date" name="expiration_date" id="expiration_date" class="form-control"
                        value="{{ old('expiration_date', optional($contract->contract_contract_parking->contract_parking_contract_annual)->expiration_date
                            ? \Carbon\Carbon::parse($contract->contract_contract_parking->contract_parking_contract_annual->expiration_date)->format('Y-m-d')
                            : '') }}">
                </div>
            @endif

            <div class="form-group mt-4">
                <label><strong>Datos de Contacto</strong></label>
                @foreach($contactInformation as $contact)
                <div class="form-check">
                    <input type="checkbox" name="contact_information[]" value="{{ $contact->id_contact_information }}" class="form-check-input"
                        {{ in_array($contact->id_contact_information, $contactIds) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $contact->type_contact }}: {{ $contact->data_contact }}</label>
                </div>
                @endforeach
            </div>

            <div class="form-group mt-4">
                <label><strong>Reglas asociadas</strong></label>
                @forelse($rules->where('type_contract', $type) as $rule)
                <div class="form-check mb-2">
                    <input type="checkbox" name="rules[]" value="{{ $rule->id_rule }}" class="form-check-input"
                        {{ in_array($rule->id_rule, $ruleIds) ? 'checked' : '' }}>
                    <label class="form-check-label">
                        <strong>{{ ucfirst($rule->name) }}</strong><br>
                        <small class="text-muted">{{ $rule->description }}</small>
                    </label>
                </div>
                @empty
                <p class="text-muted">No hay reglas disponibles para este tipo de contrato.</p>
                @endforelse
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('contratos.show', $contract->id_branch_office) }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
