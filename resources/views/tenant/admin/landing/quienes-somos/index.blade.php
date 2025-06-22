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
      <a href="{{ route('landing.quienes-somos.edit', $aboutUs) }}" 
      style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto" title="Editar">
        <i class="fas fa-pen me-1"></i> Editar
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered w-100">
          <tbody>
            <tr>
              <th width="200">Texto Superior</th>
              <td>
                {!! $aboutUs->top_text_active 
                    ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                    : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($aboutUs->top_text, 100) }}</div>
              </td>
            </tr>
            <tr>
              <th>Título Principal</th>
              <td>
                {!! $aboutUs->main_title_active 
                    ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                    : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2">{{ $aboutUs->main_title }}</div>
              </td>
            </tr>
            <tr>
              <th>Texto Secundario</th>
              <td>
                {!! $aboutUs->secondary_text_active 
                    ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                    : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($aboutUs->secondary_text, 100) }}</div>
              </td>
            </tr>
            <tr>
              <th>Botón</th>
              <td>
                {!! $aboutUs->button_active 
                    ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                    : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2">
                  <div><strong>Texto:</strong> {{ $aboutUs->button_text }}</div>
                  <div><strong>Enlace:</strong> {{ $aboutUs->button_link }}</div>
                </div>
              </td>
            </tr>
            <tr>
              <th>Colores</th>
              <td>
                <div class="d-flex flex-wrap gap-3 small">
                  @foreach([
                    'Texto Botón' => $aboutUs->button_text_color,
                    'Fondo Botón' => $aboutUs->button_color,
                    'Fondo Tarjeta' => $aboutUs->card_color,
                    'Texto Tarjeta' => $aboutUs->card_text_color,
                    'Tarjeta Video' => $aboutUs->video_card_color
                  ] as $label => $color)
                    <div class="d-flex align-items-center gap-2 me-4">
                      <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color }};border-radius:50%;border:1px solid #ccc;"></span>
                      {{ $label }}
                    </div>
                  @endforeach
                </div>
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
  table td {
    vertical-align: middle;
  }
</style>
@endpush
