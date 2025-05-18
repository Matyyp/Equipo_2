@extends('tenant.layouts.landings')

@section('title','Autos Disponibles')

@section('content')
<div 
  class="container mx-auto py-12 px-4" 
  x-data="searchCars()" 
  x-init="init()"
>
  <h2 class="text-3xl font-semibold mb-8">Autos Disponibles para Arriendo</h2>

  {{-- Buscador dinámico --}}
  <div class="flex mb-12">
    <input 
      type="text"
      x-model.debounce.300ms="search"
      @input="doSearch()"
      placeholder="Buscar por marca o modelo" 
      class="flex-1 p-2 border rounded"
    >
  </div>

  {{-- Aquí inyectaremos el grid --}}
  <div id="cars-container">
    @include('tenant.landings._cars', ['cars' => $cars])
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Función indivisible para inicializar TODOS los Swiper de la página
  function initCarousels() {
    document.querySelectorAll('.swiper-auto').forEach(el => {
      // Para evitar dobles instancias, opcionalmente destruye antes:
      if (el.swiper) el.swiper.destroy(true, true);

      el.swiper = new Swiper(el, {
        loop: true,
        effect: 'fade',
        autoplay: { delay: 5000 },
        pagination: {
          el: el.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: el.querySelector('.swiper-button-next'),
          prevEl: el.querySelector('.swiper-button-prev'),
        },
      });
    });
  }

  // Llamada inicial cuando todo el DOM esté listo
  document.addEventListener('DOMContentLoaded', initCarousels);

  // Alpine component
  function searchCars() {
    return {
      search: '',
      timer: null,

      // Método para inicializar en Alpine
      init() {
        initCarousels();
      },

      // Método que dispara la petición AJAX y re-render del partial
      doSearch() {
        clearTimeout(this.timer);
        this.timer = setTimeout(async () => {
          const res  = await fetch(
            `{{ route('landings.available.partial') }}?search=${encodeURIComponent(this.search)}`
          );
          const html = await res.text();
          document.getElementById('cars-container').innerHTML = html;

          // Y vuelves a inicializar Swiper en los nuevos slides
          initCarousels();
        }, 300);
      }
    }
  }
</script>
@endpush
