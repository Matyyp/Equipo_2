@extends('tenant.layouts.admin')

@section('title', 'Datos de la Empresa')
@section('page_title', 'Datos de la Empresa')

@section('content')
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Información General</h5>

        @if ($business)
            <a href="{{ route('empresa.edit', $business->id_business) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
        @else
            <a href="{{ route('empresa.create') }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Ingresar Perfil de Empresa
            </a>
        @endif
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Nombre de la Empresa:</strong><br>
                {{ $business->name_business ?? '-' }}
            </div>
            <div class="col-md-6">
                <strong>Datos de Transferencia:</strong><br>
                {{ $business->electronic_transfer ?? '-' }}
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
