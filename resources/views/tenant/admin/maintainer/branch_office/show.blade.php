@extends('tenant.layouts.admin')

@section('title')
Sucursal: {{ $sucursal->name_branch_offices }}
@endsection

@section('page_title', 'Detalle de la Sucursal')

@push('styles')
<style>
    table.dataTable td,
    table.dataTable th {
        border: none !important;
    }

    table.dataTable tbody tr {
        border: none !important;
    }

    table.dataTable {
        border-top: 2px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
    }

    .dataTables_paginate .pagination .page-item.active a.page-link {
        background-color: #17a2b8 !important;
        color: rgb(255, 255, 255) !important;
        border-color: #17a2b8 !important;
    }

    .dataTables_paginate .pagination .page-item .page-link {
        background-color: #eeeeee;
        color: #17a2b8 !important;
        border-color: #eeeeee;
    }

    .btn-outline-info.text-info:hover,
    .btn-outline-info.text-info:focus {
        color: #fff !important;
    }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('form.eliminar-sucursal');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas desactivar esta sucursal?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-store-alt me-2"></i> {{ $sucursal->name_branch_offices }}
            </div>
            <a href="{{ route('sucursales.index') }}"
               style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>

        <div class="card-body">
            <p><strong>Horario:</strong> {{ $sucursal->schedule }}</p>
            <p><strong>Calle:</strong> {{ $sucursal->street }}</p>
            <p><strong>Región:</strong> {{ $sucursal->branch_office_location->location_region->name_region ?? 'No asignada' }}</p>
            <p><strong>Comuna:</strong> {{ $sucursal->branch_office_location->commune ?? 'No asignada' }}</p>
            <hr>

            <div class="d-flex flex-wrap justify-content-center mt-4">
                <a href="{{ route('sucursales.edit', $sucursal->id_branch) }}" class="btn btn-outline-info text-info btn-lg rounded mx-2 mb-2">
                    <i class="fas fa-pen"></i> Editar
                </a>

                <form action="{{ route('sucursales.destroy', $sucursal->id_branch) }}" method="POST" class="mx-2 mb-2 eliminar-sucursal">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-info text-info btn-lg rounded">
                        <i class="fas fa-ban"></i> Desactivar
                    </button>
                </form>

                <a href="{{ route('servicios.show', $sucursal->id_branch) }}" class="btn btn-outline-info text-info btn-lg rounded mx-2 mb-2">
                    <i class="fas fa-concierge-bell"></i> Servicios
                </a>
                <a href="{{ route('contratos.show', $sucursal->id_branch) }}" class="btn btn-outline-info text-info btn-lg rounded mx-2 mb-2">
                    <i class="fas fa-file-contract"></i> Contratos
                </a>
                <a href="{{ url('informacion_contacto/' . $sucursal->id_branch) }}" class="btn btn-outline-info text-info btn-lg rounded mx-2 mb-2">
                    <i class="fas fa-address-book"></i> Información de Contacto
                </a>
                <a href="{{ url('trabajadores/' . $sucursal->id_branch) }}" class="btn btn-outline-info text-info btn-lg rounded mx-2 mb-2">
                    <i class="fas fa-users"></i> Trabajadores
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
