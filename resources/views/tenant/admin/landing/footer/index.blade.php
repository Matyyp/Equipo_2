@extends('tenant.layouts.admin')

@section('title', 'Gestionar Footer')
@section('page_title', 'Gestionar Footer')

@push('styles')
  <script src="https://unpkg.com/lucide@latest" defer></script>
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
          <i class="fas fa-shoe-prints mr-2"></i>
          <span>Previsualización del Footer</span>
        </div>
        <div class="col-auto ms-auto">
          <a href="{{ route('landing.footer.edit', $footer->id) }}"
            style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-pen me-1"></i> Editar
          </a>
        </div>
      </div>
    </div>

    @if($footer)
    @php
    $contacts = $footer->contact_text ? explode(',', $footer->contact_text) : [];
    $socials = $footer->social_text ? explode(',', $footer->social_text) : [];

    function getSocialIcon($url) {
        $host = parse_url($url, PHP_URL_HOST) ?? '';
        return match(true) {
            str_contains($host, 'facebook') => 'facebook',
            str_contains($host, 'instagram') => 'instagram',
            str_contains($host, 'twitter'), str_contains($host, 'x.com') => 'twitter',
            str_contains($host, 'youtube') => 'youtube',
            str_contains($host, 'linkedin') => 'linkedin',
            str_contains($host, 'tiktok') => 'video',
            default => 'globe',
        };
    }

    function cleanSocialName($url) {
        $host = parse_url($url, PHP_URL_HOST) ?? $url;
        $host = preg_replace('/^www\./', '', $host);
        return ucfirst(explode('.', $host)[0]);
    }
@endphp


    <div class="card-body">
      <footer class="p-4" style="background-color: {{ $footer->background_color }};">
        <div class="row text-center text-md-left align-items-start">

          {{-- Logo + copyright --}}
          <div class="col-md-4 mb-3">
            @if (!empty($tenantLogo))
              <img src="{{ $tenantLogo }}" alt="Logo" class="mb-2 d-block mx-auto" style="max-height: 50px;">
            @endif
            <p class="mb-0 small text-center" style="color: {{ $footer->text_color_2 }};">
              {{ $footer->copyright }} © {{ date('Y') }}
            </p>
          </div>

          {{-- Contacto --}}
          @if($footer->contact_active && count($contacts))
          <div class="col-md-4 mb-3">
            <h6 class="font-weight-bold mb-2" style="color: {{ $footer->text_color_1 }};">Contacto</h6>
            <ul class="list-unstyled small">
              @foreach($contacts as $contact)
                <li class="d-flex align-items-center mb-1">
                  <i data-lucide="mail" class="me-2" style="color: {{ $footer->text_color_1 }};"></i>
                  <span style="color: {{ $footer->text_color_2 }};">{{ trim($contact) }}</span>
                </li>
              @endforeach
            </ul>
          </div>
          @endif

          {{-- Redes Sociales --}}
          @if($footer->social_active && count($socials))
          <div class="col-md-4 mb-3">
            <h6 class="font-weight-bold mb-2" style="color: {{ $footer->text_color_1 }};">Redes Sociales</h6>
            <ul class="list-unstyled small">
              @foreach($socials as $social)
                @php
                  $url = trim($social);
                  $icon = getSocialIcon($url);
                  $name = cleanSocialName($url);
                @endphp
                <li class="d-flex align-items-center mb-1">
                  <i data-lucide="{{ $icon }}" class="me-2" style="color: {{ $footer->text_color_1 }};"></i>
                  <a href="{{ $url }}" target="_blank" style="color: {{ $footer->text_color_2 }};">
                    {{ $name }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
          @endif

        </div>
      </footer>
    </div>
    @else
      <div class="card-body text-center text-muted py-5">
        <p>No hay footer configurado aún.</p>
        <a href="{{ route('landing.footer.create') }}" class="btn btn-primary">
          <i class="fas fa-plus"></i> Crear Footer
        </a>
      </div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
  });
</script>
@endpush
