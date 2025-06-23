@extends('tenant.layouts.admin')

@section('title', 'Tipos de Vehículo')
@section('page_title', 'Tipos de Vehículo')

@push('styles')
<style>
  .vehicle-card {
    transition: all 0.3s ease;
  }

  .vehicle-card:hover {
    transform: scale(1.01);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }

  .vehicle-image {
    height: 180px;
    object-fit: cover;
    width: 100%;
  }

  .vehicle-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 10px;
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
            <i class="fas fa-car-side mr-2"></i>
            <span>Previsualización de Destacados</span>
          </div>
          <div class="col-auto ms-auto">
            <a href="{{ route('landing.vehicle.create') }}"
              style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
              <i class="fas fa-plus"></i> Nuevo
            </a>
          </div>
        </div>
    </div>


    <div class="card-body">
      @php
        use App\Models\VehicleType;
        $vehicles = VehicleType::with('image')->get();
      @endphp

      @if($vehicles->count())
        <div class="row">
          @foreach($vehicles as $vehicle)
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card vehicle-card h-100 shadow-sm rounded-2">
                @php
                  $imagePath = $vehicle->image->path ?? 'img/placeholder.jpg';
                @endphp
                <img src="{{ tenant_asset($imagePath) }}"
                     alt="{{ $vehicle->card_title }}"
                     class="vehicle-image"
                     onerror="this.onerror=null;this.src='https://via.placeholder.com/400x200?text=Sin+Imagen';">

                <div class="p-3" style="background-color: {{ $vehicle->card_background_color ?? '#f9fafb' }}; color: {{ $vehicle->text_color ?? '#111827' }};">
                  @if($vehicle->card_title_active && $vehicle->card_title)
                    <h5 class="fw-bold mb-2">{{ $vehicle->card_title }}</h5>
                  @endif

                  @if($vehicle->card_subtitle_active && $vehicle->card_subtitle)
                    <p class="mb-0">{{ $vehicle->card_subtitle }}</p>
                  @endif
                </div>

                <div class="p-3 pt-0 vehicle-actions">
                  <a href="{{ route('landing.vehicle.edit', $vehicle->id) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-pen"></i>
                  </a>

                  <form action="{{ route('landing.vehicle.destroy', $vehicle->id) }}"
                        method="POST"
                        class="delete-form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-info btn-sm">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="alert alert-warning">No hay vehículos registrados.</div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar este vehículo?',
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
