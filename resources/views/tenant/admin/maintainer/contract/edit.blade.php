@extends('tenant.layouts.admin')

@section('title', 'Editar Contrato')
@section('page_title', 'Editar Contrato')

@section('content')
<div class="container-fluid">
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

        <div class="form-group mb-4">
          <label><strong>Datos de Contacto</strong></label>
          @foreach($contactInformation as $contact)
            <div class="form-check">
              <input type="checkbox" name="contact_information[]" value="{{ $contact->id_contact_information }}" class="form-check-input"
                {{ in_array($contact->id_contact_information, $contactIds) ? 'checked' : '' }} data-type="{{ $contact->type_contact }}">
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
          <label><strong>Reglas Asociadas</strong></label>
          @forelse($rules->where('type_contract', $type) as $rule)
            <div class="form-check mb-2">
              <input type="checkbox" name="rules[]" value="{{ $rule->id_rule }}" class="form-check-input"
                {{ in_array($rule->id_rule, $ruleIds) ? 'checked' : '' }}>
              <label class="form-check-label">
                <strong>
                  {{
                    match($rule->name) {
                      'no_smoking' => 'Prohibido fumar',
                      'return_clean' => 'Debe devolverse limpio',
                      'no_pets' => 'No se permiten mascotas',
                      default => ucfirst(str_replace('_', ' ', $rule->name))
                    }
                  }}
                </strong><br>
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
            <a href="{{ route('contratos.show', $contract->id_branch_office) }}" class="btn btn-secondary mr-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              Actualizar
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
    const checkboxes = document.querySelectorAll('input[name="contact_information[]"]');

    form.addEventListener('submit', function(e) {
      const grouped = {};
      checkboxes.forEach(cb => {
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
