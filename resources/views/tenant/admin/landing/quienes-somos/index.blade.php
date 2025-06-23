@extends('tenant.layouts.admin')

@section('title', 'Gestión de Quiénes Somos')
@section('page_title', 'Gestión de Quiénes Somos')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @php
      $aboutUs = \App\Models\AboutUs::first();

      $showSection = $aboutUs && (
        $aboutUs->top_text_active || 
        $aboutUs->main_title_active || 
        $aboutUs->secondary_text_active || 
        $aboutUs->tertiary_text_active
      );
  @endphp

  <div class="card">


    <div class="card-header bg-secondary text-white">
      <div class="row w-100 align-items-center">
        <div class="col">
          <i class="fas fa-info-circle me-2"></i>
          <span>Previsualización Quiénes Somos</span>
        </div>
        <div class="col-auto ms-auto">
          <a href="{{ route('landing.quienes-somos.edit', $aboutUs) }}"
            style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-pen me-1"></i> Editar
          </a>
        </div>
      </div>
    </div>

    <div class="card-body">
      @if($showSection)
        <div class="mx-auto" style="max-width: 720px;">
          <div class="rounded shadow-lg p-4 p-md-5"
               style="background-color: {{ $aboutUs->card_color }}; color: {{ $aboutUs->card_text_color }};">

            @if($aboutUs->top_text_active && $aboutUs->top_text)
              <p class="text-uppercase small mb-2 opacity-75">{{ $aboutUs->top_text }}</p>
            @endif

            @if($aboutUs->main_title_active && $aboutUs->main_title)
              <h2 class="h4 fw-bold mb-3" style="color: {{ $aboutUs->button_color }}">{{ $aboutUs->main_title }}</h2>
            @endif

            @if($aboutUs->secondary_text_active && $aboutUs->secondary_text)
              <p class="mb-3 small" style="opacity: 0.9;">{{ $aboutUs->secondary_text }}</p>
            @endif

            @if($aboutUs->tertiary_text_active && $aboutUs->tertiary_text)
              <p class="mb-4 small" style="opacity: 0.9;">{{ $aboutUs->tertiary_text }}</p>
            @endif

            @if($aboutUs->button_active && $aboutUs->button_text)
              <a href="{{ $aboutUs->button_link ?: '#' }}" 
                 class="btn rounded-pill px-4 py-2 fw-semibold shadow-sm"
                 style="background-color: {{ $aboutUs->button_color }}; color: {{ $aboutUs->button_text_color }}">
                {{ $aboutUs->button_text }} →
              </a>
            @endif
          </div>
        </div>
      @else
        <div class="alert alert-warning">No hay contenido activo para esta sección.</div>
      @endif
    </div>
  </div>
</div>
@endsection
