@extends('tenant.layouts.admin')

@section('title', 'Editar Contrato')
@section('page_title', 'Editar Contrato')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-edit me-2"></i>Editar Contrato
    </div>

    <div class="card-body">
      <h5 class="mb-3">Sucursal: <strong>{{ $branchName ?? 'N/D' }}</strong></h5>
      <h6 class="mb-4">Tipo de Contrato: 
        <span class="badge bg-info text-dark">
          {{
            match($type) {
              'rent' => 'Arriendo',
              'parking_daily' => 'Estacionamiento Diario',
              'parking_annual' => 'Estacionamiento Mensual',
              default => strtoupper($type)
            }
          }}
        </span>
      </h6>

      @if($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Corrige los siguientes errores:
          <ul class="mb-0 mt-2">
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
          <div class="form-group mb-3">
            <label for="important_note">Nota Importante</label>
            <input type="text" name="important_note" id="important_note" class="form-control"
              value="{{ old('important_note', $contract->contract_contract_parking->contract_parking_contract_annual->important_note ?? '') }}">
          </div>

          <div class="form-group mb-3">
            <label for="expiration_date">Fecha de Expiración</label>
            <input type="date" name="expiration_date" id="expiration_date" class="form-control"
              value="{{ old('expiration_date', optional($contract->contract_contract_parking->contract_parking_contract_annual)->expiration_date
              ? \Carbon\Carbon::parse($contract->contract_contract_parking->contract_parking_contract_annual->expiration_date)->format('Y-m-d')
              : '') }}">
          </div>
        @endif

        <div class="form-group mb-4">
          <label><strong>Datos de Contacto</strong></label>
          @foreach($contactInformation as $contact)
            <div class="form-check">
              <input type="checkbox" name="contact_information[]" value="{{ $contact->id_contact_information }}" class="form-check-input"
                {{ in_array($contact->id_contact_information, $contactIds) ? 'checked' : '' }}>
              <label class="form-check-label">{{ $contact->type_contact }}: {{ $contact->data_contact }}</label>
            </div>
          @endforeach
        </div>

        <div class="form-group mb-4">
          <label><strong>Reglas Asociadas</strong></label>
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

        <!-- BOTONES -->
        <div class="form-group row justify-content-end mt-4">
          <div class="col-auto">
            <a href="{{ route('contratos.show', $contract->id_branch_office) }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-1"></i> Actualizar
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
