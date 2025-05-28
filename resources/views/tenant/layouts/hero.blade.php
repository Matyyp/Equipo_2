@php
    use App\Models\Heroes;

    $heroes = Heroes::with('image')->get();
@endphp

@if($heroes->count() > 0 && $heroes->every(fn($h) => $h->image))
<section class="relative h-[80vh] w-full overflow-x-hidden">
  <div class="swiper h-full overflow-hidden">
    <div class="swiper-wrapper">
      @foreach($heroes as $hero)
        <div class="swiper-slide h-full bg-cover bg-center relative"
          style="background-image: url('{{ tenant_asset($hero->image->path) }}')">
          <div class="absolute inset-0 bg-black/50"></div>
          <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4"
               style="color: {{ $hero->text_color }}">
            
            {{-- Título --}}
            @if($hero->title_active)
              <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" data-aos="fade-down">
                {{ $hero->title }}
              </h1>
            @endif

            {{-- Subtítulo --}}
            @if($hero->subtitle_active)
              <p class="text-lg sm:text-xl max-w-xl mb-6" data-aos="fade-up">
                {{ $hero->subtitle }}
              </p>
            @endif

            {{-- Botón --}}
            @if($hero->button_active)
              <a href="{{ $hero->button_url }}"
                 class="px-6 py-3 rounded text-sm sm:text-base"
                 style="background-color: {{ $hero->button_color }}; color: {{ $hero->text_color }}"
                 data-aos="zoom-in">
                {{ $hero->button_text }}
              </a>
            @endif

          </div>
        </div>
      @endforeach
    </div>
    <div class="swiper-pagination absolute bottom-4 w-full text-center z-20"></div>
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
  </div>
</section>


@push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.swiper', {
                    loop: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                });
            });
        </script>
    @endpush
@endif
