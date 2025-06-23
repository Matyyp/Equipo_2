@extends('tenant.layouts.admin')

@section('title', 'Contenedor de Imágenes')
@section('page_title', 'Contenedor de Imágenes')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    .image-card {
      position: relative;
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .image-card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .image-actions {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
    }

    .image-actions a,
    .image-actions button {
      display: inline-block;
      margin-left: 0.25rem;
      background: transparent;
      border: none;
      color: white;
      cursor: pointer;
    }
  </style>
@endpush

@section('content')
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
      <div class="card-header bg-secondary text-white">
        <div class="row w-100 align-items-center">
          <div class="col">
            <i class="fas fa-images mr-2"></i>
            <span>Imágenes del Contenedor</span>
          </div>
          <div class="col-auto ms-auto">
            <a href="{{ route('landing.container-image.create') }}"
              style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
              <i class="fas fa-plus"></i> Nuevo
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          @foreach($images as $image)
            <div class="col-md-4 mb-4">
              <div class="image-card">
                <img src="{{ tenant_asset($image->path) }}" alt="Imagen" class="rounded w-100">

                <div class="image-actions">
                  <a href="{{ route('landing.container-image.edit', $image) }}" class="btn btn-sm btn-info" title="Editar">
                    <i class="fas fa-pen"></i>
                  </a>
                  <button class="btn btn-sm btn-info delete-btn" data-id="{{ $image->id }}">
                    <i class="fas fa-trash"></i>
                  </button>
                  <form id="delete-form-{{ $image->id }}" action="{{ route('landing.container-image.destroy', $image) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');

        Swal.fire({
          title: '¿Estás seguro de Eliminar esta Imagen?',
          text: "¡Esta acción no se puede deshacer!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
          }
        });
      });
    });
  </script>
@endpush
