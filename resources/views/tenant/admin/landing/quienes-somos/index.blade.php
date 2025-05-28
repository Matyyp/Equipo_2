@extends('tenant.layouts.admin')

@section('title', 'Gestión de Quiénes Somos')
@section('page_title', 'Gestión de Quiénes Somos')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-info-circle mr-2"></i>Quiénes Somos</div>

    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="quienes-somos-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Texto Superior</th>
              <th>Título Principal</th>
              <th>Texto Secundario</th>
              <th>Botón</th>
              <th>Colores</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <!-- Texto Superior -->
              <td>
                @if($aboutUs->top_text_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($aboutUs->top_text, 50) }}</div>
              </td>
              
              <!-- Título Principal -->
              <td>
                @if($aboutUs->main_title_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2">{{ $aboutUs->main_title }}</div>
              </td>
              
              <!-- Texto Secundario -->
              <td>
                @if($aboutUs->secondary_text_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($aboutUs->secondary_text, 50) }}</div>
              </td>
              
              <!-- Botón -->
              <td>
                @if($aboutUs->button_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2">
                  <span class="d-block"><strong>Texto:</strong> {{ $aboutUs->button_text }}</span>
                  <span class="d-block"><strong>Enlace:</strong> {{ $aboutUs->button_link }}</span>
                </div>
              </td>
              
              <!-- Colores -->
              <td>
                <div class="d-flex flex-column gap-1 small">
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $aboutUs->button_text_color }};border-radius:50%;border:1px solid #ccc;"></span>
                    Texto Botón
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $aboutUs->button_color }};border-radius:50%;border:1px solid #ccc;"></span>
                    Fondo Botón
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $aboutUs->card_color }};border-radius:50%;border:1px solid #ccc;"></span>
                    Fondo Tarjeta
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $aboutUs->card_text_color }};border-radius:50%;border:1px solid #ccc;"></span>
                    Texto Tarjeta
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-block;width:15px;height:15px;background-color:{{ $aboutUs->video_card_color }};border-radius:50%;border:1px solid #ccc;"></span>
                    Color Tarjeta Video
                  </div>
                </div>
              </td>
              
              <!-- Acciones -->
              <td>
                <a href="{{ route('landing.quienes-somos.edit', $aboutUs) }}" class="btn btn-outline-warning btn-sm text-dark" title="Editar">
                  <i class="fas fa-pen"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
  }
  #quienes-somos-table td {
    vertical-align: middle;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  $('#quienes-somos-table').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    searching: false,
    paging: false,
    info: false,
    ordering: false
  });
});
</script>
@endpush