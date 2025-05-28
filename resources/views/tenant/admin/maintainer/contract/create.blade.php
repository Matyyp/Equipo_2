@extends('tenant.layouts.admin')

@section('title', 'Crear Contrato')
@section('page_title', 'Nuevo Contrato')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-file-contract mr-2"></i>Crear Contrato
    </div>

    <div class="card-body">
      <h5 class="mb-3">Sucursal: <strong>{{ $branchName ?? 'N/D' }}</strong></h5>
      <h6 class="mb-4">
        Tipo de Contrato: 
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

      <form action="{{ route('contratos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="branch_id" value="{{ $branchId }}">
        <input type="hidden" name="contract_type" value="{{ $type }}">

        <div class="form-group mb-4">
          <label class="form-label">Datos de Contacto</label>
            @foreach($contactInformation as $contact)
              <div class="form-check">
                <input type="checkbox"
                      name="contact_information[]"
                      value="{{ $contact->id_contact_information }}"
                      class="form-check-input"
                      data-type="{{ $contact->type_contact }}">
                <label class="form-check-label">
                  {{
                    match($contact->type_contact) {
                      'email' => 'Correo Electrónico',
                      'phone' => 'Teléfono Fijo',
                      'mobile' => 'Teléfono Celular',
                      'whatsapp' => 'WhatsApp',
                      'website' => 'Sitio Web',
                      'social' => 'Red Social',
                      default => ucfirst($contact->type_contact)
                    }
                  }}: {{ $contact->data_contact }}
                </label>
              </div>
            @endforeach

        </div>

        <div class="form-group mb-4">
          <label class="form-label">Reglas Asociadas</label>
          @forelse($rules as $rule)
            <div class="form-check mb-2">
              <input type="checkbox" name="rules[]" value="{{ $rule->id_rule }}" class="form-check-input">
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
            <a href="{{ route('contratos.show', $branchId) }}" class="btn btn-secondary mr-1">
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
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const contactCheckboxes = document.querySelectorAll('input[name="contact_information[]"]');

    form.addEventListener('submit', function(e) {
      const grouped = {};

      contactCheckboxes.forEach(cb => {
        if (cb.checked) {
          const type = cb.dataset.type;
          grouped[type] = grouped[type] ? grouped[type] + 1 : 1;
        }
      });

      for (const [type, count] of Object.entries(grouped)) {
        if (count > 3) {
          e.preventDefault();
          alert(`Solo puedes seleccionar hasta 3 contactos del tipo: ${type}`);
          return;
        }
      }
    });
  });
</script>
@endpush

