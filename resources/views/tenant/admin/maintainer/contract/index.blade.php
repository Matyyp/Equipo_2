@extends('tenant.layouts.admin')

@section('title', 'Listado de Contratos')
@section('page_title', 'Contratos por Sucursal')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      <i class="fas fa-file-contract me-2"></i>Contratos disponibles para la sucursal
    </div>

    <div class="card-body">
      {{-- Alerta si faltan datos --}}
      @if(!$hasContactInfo || !$hasRules)
        <div class="alert alert-warning d-flex flex-column gap-2 mb-4">
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Antes de activar contratos debes completar:</strong>
          </div>
          <ul class="mb-0 ms-4">
            @unless($hasContactInfo)
              <li>
                Información de contacto —
                <a href="{{ url('/informacion_contacto/' . $branchId) }}" class="btn btn-sm btn-outline-warning">
                  Ir a Información de Contacto
                </a>
              </li>
            @endunless
            @unless($hasRules)
              <li>
                Reglas de contrato —
                <a href="{{ route('reglas.index') }}" class="btn btn-sm btn-outline-warning">
                  Ir a Reglas
                </a>
              </li>
            @endunless
          </ul>
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Tipo de Contrato</th>
              <th class="text-center">Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @php
              $tipos = [
                'rent' => 'Arriendo',
                'parking_daily' => 'Estacionamiento Diario',
                'parking_annual' => 'Estacionamiento Mensual'
              ];
            @endphp

            @foreach($tipos as $key => $nombre)
              @php
                $idKey = "id_" . $key;
                $contrato = $contracts->firstWhere($idKey);
              @endphp
              <tr>
                <td>{{ $nombre }}</td>
                <td class="text-center">
                  @if($contrato)
                    <span class="badge bg-success">Activo</span>
                  @else
                    <span class="badge bg-secondary">Inactivo</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($contrato)
                    <a href="{{ route('contratos.edit', $contrato['id_contract']) }}" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit me-1"></i> Editar
                    </a>
                  @else
                    <a 
                      href="{{ $hasContactInfo && $hasRules ? route('contratos.create', ['branch' => $branchId, 'type' => $key]) : '#' }}"
                      class="btn btn-success btn-sm {{ (!$hasContactInfo || !$hasRules) ? 'disabled' : '' }}"
                      title="{{ (!$hasContactInfo || !$hasRules) ? 'Debe completar contacto y reglas para activar' : '' }}"
                    >
                      <i class="fas fa-plus me-1"></i> Activar
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Botón de volver -->
    <div class="card-footer d-flex justify-content-end">
      <a href="{{ route('sucursales.index') }}" class="btn btn-secondary ">
        <i class="fas fa-arrow-left me-1"></i> Volver a Sucursales
      </a>
    </div>
  </div>
</div>
@endsection
