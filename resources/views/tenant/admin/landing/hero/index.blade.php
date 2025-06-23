@extends('tenant.layouts.admin')

@section('title', 'Hero Landing')
@section('page_title', 'Hero Landing')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    .hero-preview {
      height: 300px;
      background-size: cover;
      background-position: center;
      position: relative;
      border-radius: 0.5rem;
      overflow: hidden;
      color: white;
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .hero-content {
      position: absolute;
      z-index: 10;
      inset: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 1rem;
    }

    .hero-actions {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 20;
    }

    .hero-actions a,
    .hero-actions button {
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
            <i class="fas fa-photo-video mr-2"></i>
            <span>Previsualización de Heroes</span>
          </div>
          <div class="col-auto ms-auto">
            <a href="{{ route('landing.hero.create') }}"
              style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
              <i class="fas fa-plus"></i> Nuevo
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach($heroes as $hero)
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="hero-preview" style="background-image: url('{{ tenant_asset($hero->image->path) }}')">
                <div class="hero-overlay"></div>
                <div class="hero-actions">
                  <a href="{{ route('landing.hero.edit', $hero) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-pen"></i>
                  </a>
                  <button class="btn btn-sm btn-info delete-btn" data-id="{{ $hero->id }}">
                    <i class="fas fa-trash"></i>
                  </button>
                  <form id="delete-form-{{ $hero->id }}" action="{{ route('landing.hero.destroy', $hero) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </div>
                <div class="hero-content" style="color: {{ $hero->text_color }}">
                  @if($hero->title_active)
                    <h5>{{ $hero->title }}</h5>
                  @endif
                  @if($hero->subtitle_active)
                    <p>{{ $hero->subtitle }}</p>
                  @endif
                  @if($hero->button_active)
                    <a href="#" class="btn btn-sm" style="background-color: {{ $hero->button_color }}; color: {{ $hero->text_color }}">
                      {{ $hero->button_text }}
                    </a>
                  @endif
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
          title: '¿Estás seguro de Eliminar este Slide?',
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
