@extends('tenant.layouts.admin')

@section('title', 'Servicios')
@section('page_title', 'Gestión de Servicios')

@push('styles')
<style>
    .admin-service-image-container {
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border: 4px solid;
    }

    .admin-service-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-service-content {
        border-radius: 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 1.5rem;
    }

    .admin-service-actions .btn {
        min-width: 100px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Contenedor principal --}}
    <div class="card">


        <div class="card-header bg-secondary text-white">
        <div class="row w-100 align-items-center">
            <div class="col">
            <i class="fas fa-photo-video mr-2"></i>
            <span>Previsualización Servicios</span>
            </div>
            <div class="col-auto ms-auto">
            <a href="{{ route('landing.service.create') }}"
                style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
                <i class="fas fa-plus"></i> Nuevo
            </a>
            </div>
        </div>
        </div>

        <div class="card-body">
            @if($services->count() > 0)
                @foreach($services as $key => $service)
                    @php
                        $hasImage = $service->image && $service->image->path;
                        $hasContent = ($service->title_active && $service->title) || 
                                      ($service->secondary_text_active && $service->secondary_text) ||
                                      ($service->small_text_active && $service->small_text);
                    @endphp

                    @if($hasImage || $hasContent)
                        <section class="py-5 {{ $key % 2 == 0 ? 'bg-gray-50' : 'bg-gray-100' }}">
                            <div class="container">
                                <div class="row align-items-center {{ $key % 2 != 0 ? 'flex-md-row-reverse' : '' }}">
                                    @if($hasImage)
                                        <div class="col-md-6 mb-4 mb-md-0">
                                            <div class="admin-service-image-container" style="border-color: {{ $service->card_background_color }};">
                                                <img src="{{ tenant_asset($service->image->path) }}"
                                                    alt="{{ $service->title }}"
                                                    class="admin-service-image"
                                                    onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text=Sin+Imagen';">
                                            </div>
                                        </div>
                                    @endif

                                    @if($hasContent)
                                        <div class="col-md-6">
                                            <div class="admin-service-content" style="background-color: {{ $service->card_background_color }};">
                                                @if($service->title_active && $service->title)
                                                    <h4 class="fw-bold mb-3" style="color: {{ $service->title_color }};">{{ $service->title }}</h4>
                                                @endif

                                                @if($service->secondary_text_active && $service->secondary_text)
                                                    <p class="mb-2" style="color: {{ $service->secondary_text_color }};">{{ $service->secondary_text }}</p>
                                                @endif

                                                @if($service->small_text_active && $service->small_text)
                                                    <p class="text-muted fst-italic" style="color: {{ $service->small_text_color }};">{{ $service->small_text }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Acciones --}}
                                <div class="d-flex justify-content-end mt-3 gap-2">
                                    <a href="{{ route('landing.service.edit', ['serviceLanding' => $service]) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-pen"></i> 
                                    </a>

                                    <form action="{{ route('landing.service.destroy', ['serviceLanding' => $service]) }}"
                                          method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-trash"></i> 
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </section>
                    @endif
                @endforeach
            @else
                <div class="alert alert-warning mb-0">
                    No hay servicios registrados.
                </div>
            @endif
        </div> {{-- Fin card-body --}}
    </div> {{-- Fin card --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Eliminar este servicio?',
                    text: 'Esta acción no se puede deshacer',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
