@extends('central.layouts.app')

@section('title', 'Gestión de Clientes')
@section('page_title', 'Gestión de Clientes')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-left: 4px solid #28a745 !important;">
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

    <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, rgba(30,60,114,0.05) 0%, rgba(42,82,152,0.03) 100%); border-bottom: 1px solid rgba(0,0,0,0.05);">
            <h3 class="card-title mb-0" style="color: #1e3c72; font-weight: 600;">
                <i class="fas fa-building mr-2" style="color: #2a5298;"></i>Clientes Registrados
            </h3>
            <a href="{{ route('tenants.create') }}" class="btn btn-primary shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
                <i class="fas fa-plus mr-1"></i> Nuevo Cliente
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: rgba(30,60,114,0.05);">
                        <tr>
                            <th width="10%" class="py-3 px-4 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">ID</th>
                            <th width="30%" class="py-3 px-4 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">Dominio</th>
                            <th width="20%" class="py-3 px-4 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">Estado</th>
                            <th width="10%" class="text-center py-3 px-4 text-uppercase small font-weight-bold" style="color: #1e3c72; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $tenant)
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.03); transition: all 0.2s;">
                            <td class="align-middle px-4">
                                <span class="badge" style="background: rgba(30,60,114,0.1); color: #1e3c72; border-radius: 50px; font-weight: 600;">#{{ $tenant->id }}</span>
                            </td>
                            <td class="align-middle px-4">
                                @if($tenant->domains->first())
                                <a href="http://{{ $tenant->domains->first()->domain }}" 
                                   target="_blank" 
                                   class="text-primary d-flex align-items-center font-weight-medium"
                                   style="text-decoration: none;">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px;">
                                        <i class="fas fa-external-link-alt fa-xs" ></i>
                                    </div>
                                    {{ $tenant->domains->first()->domain }}
                                </a>
                                @else
                                <span class="text-muted font-italic">Sin dominio</span>
                                @endif
                            </td>
                            <td class="align-middle px-4">
                                <span class="badge badge-pill" style="background: rgba(40,167,69,0.1); color: #28a745; font-weight: 500;">
                                    <i class="fas fa-check-circle mr-1"></i> Activo
                                </span>
                            </td>
                            <td class="text-center align-middle px-4">
                                <div class="d-flex justify-content-center">
                                    <form id="delete-form-{{ $tenant->id }}" action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-icon delete-btn"
                                                data-id="{{ $tenant->id }}"
                                                style="background: rgba(220,53,69,0.05); color: #dc3545; border-radius: 8px; width: 32px; height: 32px;"
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
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-building fa-2x" style="color: #1e3c72;"></i>
                                    </div>
                                    <h4 class="text-dark mb-2" style="font-weight: 600;">No hay clientes registrados</h4>
                                    <p class="text-muted mb-4">Comienza agregando tu primer cliente</p>
                                    <a href="{{ route('tenants.create') }}" class="btn btn-primary shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
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
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Mostrando <span class="font-weight-bold">{{ $tenants->firstItem() }}</span> a <span class="font-weight-bold">{{ $tenants->lastItem() }}</span> de <span class="font-weight-bold">{{ $tenants->total() }}</span> registros
            </div>
            <div class="pagination-container">
                {{ $tenants->links() }}
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
    }
    
    .table thead th {
        border-top: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
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
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-color: #1e3c72;
    }
    
    .page-link {
        color: #1e3c72;
        border-radius: 6px !important;
        margin: 0 3px;
        border: 1px solid rgba(0,0,0,0.05);
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
                `
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush