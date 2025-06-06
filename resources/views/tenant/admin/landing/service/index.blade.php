@extends('tenant.layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Gestión de Servicios')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
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
        color:rgb(255, 255, 255) !important;
        border-color: #17a2b8 !important; 
        }


        .dataTables_paginate .pagination .page-item .page-link {
        background-color: #eeeeee;
        color: #17a2b8 !important;
        border-color: #eeeeee;
        }

  </style>
@endpush

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <div><i class="fas fa-photo-video mr-2"></i>Servicios Landing</div>
        <a href="{{ route('landing.service.create') }}"
         style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
        <i class="fas fa-plus"></i> Nuevo
      </a>
        </div>
        
        <div class="card-body ">
            <div class="table-responsive">
                <table id="services-table" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th width="100">Imagen</th>
                            <th>Título</th>
                            <th>Texto Secundario</th>
                            <th>Texto Pequeño</th>
                            <th width="150">Colores</th>
                            <th width="120" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#services-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('landing.service.data') }}",
                type: "GET",
                error: function(xhr, error, thrown) {
                    console.log("Error en DataTables:", xhr, error, thrown);
                }
            },
            columns: [
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'secondary_text', name: 'secondary_text' },
                { data: 'small_text', name: 'small_text' },
                { data: 'colores', name: 'colores', orderable: false, searchable: false, className: 'text-center' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
    </script>
@endpush