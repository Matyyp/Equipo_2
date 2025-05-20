@extends('tenant.layouts.admin')

@section('title', 'Datos de la Empresa')
@section('page_title', 'Datos de la Empresa')

@section('content')
<div class="card">
    <div class="card-header bg-secondary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-building me-2"></i>Información General
            </h5>

            @if ($business)
                <a href="{{ route('empresa.edit', $business->id_business) }}" class="btn btn-sm btn-outline-info me-1">
                    <i class="fas fa-edit"></i> Editar Perfil
                </a>
            @else
                <a href="{{ route('empresa.create') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Ingresar Perfil de Empresa
                </a>
            @endif
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Nombre de la Empresa:</strong><br>
                {{ $business->name_business ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <strong>Logo:</strong><br>
                @if ($business?->logo)
                    <img src="/storage/tenants/{{ request()->getHost() }}/imagenes/{{ $business->logo }}"
                         alt="Logo del Negocio" class="img-thumbnail mt-2" width="150">
                @else
                    <span class="text-muted">No hay logo disponible.</span>
                @endif
            </div>
        </div>

        {{-- Cuentas Bancarias --}}
        @if ($business?->business_bank && count($business->business_bank))
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-university me-1"></i> Cuentas Bancarias Registradas</strong>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>RUT</th>
                                    <th>N° Cuenta</th>
                                    <th>Banco</th>
                                    <th>Tipo de Cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($business->business_bank as $bank)
                                    <tr>
                                        <td>{{ $bank->name }}</td>
                                        <td>{{ $bank->rut }}</td>
                                        <td>{{ $bank->account_number }}</td>
                                        <td>{{ $bank->bank_detail_bank->name_bank ?? '-' }}</td>
                                        <td>{{ $bank->bank_detail_type_account->name_type_account ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-1"></i> No hay cuentas bancarias registradas.
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
@endpush
