@extends('central.layouts.app')

@section('title', 'Gestión de Clientes')
@section('page_title', 'Gestión de Clientes')

@section('content')
<div class="container-fluid px-0 px-sm-2">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mx-2 mt-3" role="alert" style="border-left: 4px solid #28a745 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle mr-2 fa-lg"></i>
            <div class="font-weight-bold">
                {{ session('success') }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow border-0 mx-2 mt-3 mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-3" style="background: linear-gradient(135deg, rgba(30,60,114,0.05) 0%, rgba(42,82,152,0.03) 100%); border-bottom: 1px solid rgba(0,0,0,0.05);">
            <h3 class="card-title mb-2 mb-md-0 text-center text-md-left" style="color: #1e3c72; font-weight: 600; font-size: 1.2rem;">
                <i class="fas fa-building mr-2" style="color: #2a5298;"></i>Clientes Registrados
            </h3>
            <a href="{{ route('tenants.create') }}" class="btn btn-primary shadow-sm w-100 w-md-auto mt-2 mt-md-0" style="border-radius: 8px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; padding: 0.5rem 1rem;">
                <i class="fas fa-plus mr-1"></i> Nuevo Cliente
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: rgba(30,60,114,0.05);">
                        <tr>
                            <th class="py-3 px-2 px-md-3 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">ID</th>
                            <th class="py-3 px-2 px-md-3 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">Dominio</th>
                            <th class="py-3 px-2 px-md-3 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">Estado</th>
                            <th class="text-center py-3 px-2 px-md-3 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.03); transition: all 0.2s;">
                            <td class="align-middle px-2 px-md-3" data-label="ID">
                                <span class="badge" style="background: rgba(30,60,114,0.1); color: #1e3c72; border-radius: 50px; font-weight: 600;">#{{ $tenant->id }}</span>
                            </td>
                            <td class="align-middle px-2 px-md-3" data-label="Dominio">
                                @if($tenant->domains->first())
                                <a href="http://{{ $tenant->domains->first()->domain }}" 
                                   target="_blank" 
                                   class="text-primary d-flex align-items-center font-weight-medium"
                                   style="text-decoration: none;">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px;">
                                        <i class="fas fa-external-link-alt fa-xs"></i>
                                    </div>
                                    <span class="text-truncate" style="max-width: 150px; display: inline-block;">{{ $tenant->domains->first()->domain }}</span>
                                </a>
                                @else
                                <span class="text-muted font-italic">Sin dominio</span>
                                @endif
                            </td>
                            <td class="align-middle px-2 px-md-3" data-label="Estado">
                                <span class="badge badge-pill" style="background: rgba(40,167,69,0.1); color: #28a745; font-weight: 500;">
                                    <i class="fas fa-check-circle mr-1"></i> Activo
                                </span>
                            </td>
                            <td class="text-center align-middle px-2 px-md-3" data-label="Acciones">
                                <div class="d-flex justify-content-center">
                                    <form id="delete-form-{{ $tenant->id }}" action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-icon delete-btn"
                                                data-id="{{ $tenant->id }}"
                                                style="background: rgba(220,53,69,0.05); color: #dc3545; border-radius: 8px; width: 36px; height: 36px;"
                                                data-toggle="tooltip" 
                                                title="Eliminar cliente">
                                            <i class="fas fa-trash fa-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-building fa-lg" style="color: #1e3c72;"></i>
                                    </div>
                                    <h4 class="text-dark mb-2" style="font-weight: 600; font-size: 1.1rem;">No hay clientes registrados</h4>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">Comienza agregando tu primer cliente</p>
                                    <a href="{{ route('tenants.create') }}" class="btn btn-primary shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; padding: 0.5rem 1rem; font-size: 0.9rem;">
                                        <i class="fas fa-plus mr-2"></i> Crear primer cliente
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tenants->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center px-3">
            <div class="text-muted small mb-2 mb-md-0">
                Mostrando <span class="font-weight-bold">{{ $tenants->firstItem() }}</span> a <span class="font-weight-bold">{{ $tenants->lastItem() }}</span> de <span class="font-weight-bold">{{ $tenants->total() }}</span> registros
            </div>
            <div class="pagination-container">
                {{ $tenants->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .table thead th {
        border-top: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        white-space: nowrap;
    }
    
    .table tbody tr:hover {
        background: rgba(30,60,114,0.02) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    
    .pagination-container .pagination {
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-color: #1e3c72;
    }
    
    .page-link {
        color: #1e3c72;
        border-radius: 6px !important;
        margin: 2px;
        border: 1px solid rgba(0,0,0,0.05);
        padding: 0.375rem 0.5rem;
    }

    /* Mejoras específicas para móviles */
    @media (max-width: 767.98px) {
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        
        .card {
            margin-left: 10px;
            margin-right: 10px;
            border-radius: 10px;
        }
        
        .card-header {
            flex-direction: column;
            align-items: stretch;
            padding: 1rem;
        }
        
        .card-title {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .btn {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
        }
        
        .table-responsive {
            border-radius: 0;
        }
        
        .table thead {
            display: none;
        }
        
        .table tbody tr {
            display: block;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 10px;
            position: relative;
        }
        
        .table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            text-align: right;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #1e3c72;
            margin-right: auto;
            padding-right: 1rem;
            text-align: left;
        }
        
        .table td:last-child {
            border-bottom: 0;
            justify-content: flex-end;
        }
        
        .table td:last-child::before {
            display: none;
        }
        
        .badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.65rem;
        }
        
        .btn-icon {
            width: 40px !important;
            height: 40px !important;
        }
    }

    @media (max-width: 575.98px) {
        .card-footer {
            flex-direction: column;
            align-items: center;
        }
        
        .pagination-container {
            width: 100%;
            overflow-x: auto;
            padding-bottom: 5px;
        }
        
        .pagination {
            flex-wrap: nowrap;
        }
        
        .page-item .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
    }

    @media (min-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            placement: 'top',
            trigger: 'hover'
        });

        // SweetAlert for delete confirmation
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            var tenantId = $(this).data('id');
            var form = $('#delete-form-' + tenantId);
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto! Todos los datos asociados se eliminarán permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                backdrop: `
                    rgba(30,60,114,0.1)
                    url("/images/alert-arrow.png")
                    left bottom
                    no-repeat
                `,
                width: window.innerWidth > 576 ? '400px' : '90%'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush