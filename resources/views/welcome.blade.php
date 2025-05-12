@extends('layouts.app2')

@section('content')

<!-- Hero (sin padding lateral, ancho 100%) -->
<section class="relative h-[80vh] w-full overflow-x-hidden">
  <div class="swiper h-full overflow-hidden">
    <div class="swiper-wrapper">
      <!-- Slide 1 -->
      <div class="swiper-slide h-full bg-cover bg-center relative" style="background-image: url('{{ asset('img/hero.jpg') }}')">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white text-center px-4">
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" data-aos="fade-down">
            Explora la Patagonia con seguridad y confort
          </h1>
          <p class="text-lg sm:text-xl max-w-xl mb-6" data-aos="fade-up">
            Vehículos preparados para cada desafío del sur de Chile.
          </p>
          <a href="#" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded text-sm sm:text-base" data-aos="zoom-in">
            Ver vehículos
          </a>
        </div>
      </div>
      <!-- Slide 2 -->
      <div class="swiper-slide h-full bg-cover bg-center relative" style="background-image: url('{{ asset('img/servicios.jpg') }}')">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white text-center px-4">
          <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" data-aos="fade-down">
            Agenda servicios adicionales
          </h2>
          <p class="text-lg sm:text-xl max-w-xl mb-6" data-aos="fade-up">
            Lavado completo y estacionamiento seguro en Balmaceda.
          </p>
          <a href="#" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded text-sm sm:text-base" data-aos="zoom-in">
            Agenda ahora
          </a>
        </div>
      </div>
    </div>
    <div class="swiper-pagination absolute bottom-4 w-full text-center z-20"></div>
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
  </div>
</section>

<!-- Vehículos -->
<section class="py-16 bg-gray-100">
  <div class="container mx-auto px-4 overflow-x-hidden">
    <h2 class="text-3xl font-bold text-center mb-10">
      Servicio personalizado en la región de Aysén
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      @foreach ([
        ['title'=>'Pick Up','desc'=>'Camionetas 4x4 listas.','img'=>'pickup.jpg'],
        ['title'=>'Todo Terreno','desc'=>'Ripio y montaña.','img'=>'4x4.jpg'],
        ['title'=>'Mini Bus','desc'=>'Viajes grupales.','img'=>'minibus.jpg'],
        ['title'=>'SUV','desc'=>'Confort y tecnología.','img'=>'suv.jpg'],
      ] as $v)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition" data-aos="fade-up">
          <img src="{{ asset('img/'.$v['img']) }}" alt="{{ $v['title'] }}" class="w-full h-48 object-cover">
          <div class="p-5">
            <h3 class="text-xl font-bold mb-2">{{ $v['title'] }}</h3>
            <p class="text-sm text-gray-600">{{ $v['desc'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
    <div class="mt-10 flex justify-center">
      <a href="#cotizar" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-xl text-white font-semibold shadow-md">
        Cotiza tu vehículo ideal →
      </a>
    </div>
  </div>
</section>

<!-- Quiénes Somos -->
<section id='quienes-somos' class="py-16 bg-white">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10">
    <div data-aos="fade-right" class="flex flex-col">
      <div class="flex gap-2 mb-4">
        <button onclick="setVideo('https://www.youtube.com/embed/k2-LUK_vmOA')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md">
          Video 1
        </button>
        <button onclick="setVideo('https://www.youtube.com/embed/zleIaEIBs2M')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md">
          Video 2
        </button>
        <button onclick="setVideo('https://www.youtube.com/embed/Bxo2JkiqG_o')" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md">
          Video 3
        </button>
      </div>
      <div class="aspect-video rounded-2xl overflow-hidden shadow-xl border-4 border-orange-500">
        <iframe id="videoFrame" src="https://www.youtube.com/embed/k2-LUK_vmOA" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen
          class="w-full h-full"></iframe>
      </div>
    </div>
    <div data-aos="fade-left" class="flex items-center">
      <div class="bg-[#0b1a2b] text-white rounded-2xl shadow-lg p-8 space-y-5 w-full">
        <p class="uppercase text-sm text-gray-300 tracking-wider">Nuestra Misión</p>
        <h2 class="text-3xl font-bold text-orange-400">¿Quiénes Somos?</h2>
        <p class="text-base text-gray-200 leading-relaxed">
          Somos tu alternativa para el arriendo de vehículos en la Patagonia.
        </p>
        <p class="text-base text-gray-200 leading-relaxed">
          Ofrecemos un servicio completo, acompañándote en cada ruta con conocimiento local.
        </p>
        <a href="#cotizar" class="mt-4 bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-xl text-white font-semibold shadow-md inline-block">
          Conócenos más →
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Estacionamiento y Lavado -->
<section class="py-20 bg-gray-50">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    <div data-aos="fade-right" class="overflow-hidden rounded-2xl shadow-xl border-4 border-blue-200">
      <img src="{{ asset('img/lavado.jpg') }}" alt="Lavado" class="w-full h-full object-cover">
    </div>
    <div data-aos="fade-left">
      <div class="bg-[#0b1a2b] text-white rounded-2xl shadow-2xl p-6 space-y-4">
        <h3 class="text-2xl font-bold text-orange-600">Lavado Completo</h3>
        <p class="text-lg leading-relaxed">Exterior e interior con atención al detalle.</p>
        <p class="text-sm text-gray-400 italic">*Reserva con anticipación.</p>
      </div>
    </div>
  </div>
</section>

<!-- Planes de Estacionamiento -->
<section class="py-20 bg-gray-100">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    <div data-aos="fade-right">
      <div class="bg-orange-500 text-white rounded-2xl shadow-2xl p-6 space-y-4">
        <h3 class="text-2xl font-bold text-[#0b1a2b]">Planes de Estacionamiento</h3>
        <p class="text-lg leading-relaxed">Vigilado por día, semana, mes o año.</p>
        <p class="text-sm text-gray-500 italic">*Revisa tu contrato antes de contratar.</p>
      </div>
    </div>
    <div data-aos="fade-left" class="overflow-hidden rounded-2xl shadow-xl border-4 border-orange-300">
      <img src="{{ asset('img/estacionamiento.jpg') }}" alt="Estacionamiento" class="w-full h-full object-cover">
    </div>
  </div>
</section>

<!-- Carrusel de imágenes -->
<section class="py-16 bg-white">
  <div class="container mx-auto px-4 text-center">
    <h2 class="text-3xl font-bold mb-10">Explora la Patagonia en imágenes</h2>
    <div class="swiper image-carousel relative overflow-hidden">
      <div class="swiper-wrapper">
        @foreach (['paisaje1.jpg','paisaje2.jpg','paisaje3.jpg'] as $img)
          <div class="swiper-slide">
            <img src="{{ asset('img/'.$img) }}" alt="Patagonia" class="w-full h-[300px] object-cover rounded-xl shadow-lg">
          </div>
        @endforeach
      </div>
      <div class="swiper-button-next text-orange-500"></div>
      <div class="swiper-button-prev text-orange-500"></div>
      <div class="swiper-pagination mt-4"></div>
    </div>
  </div>
</section>

<!-- Mapa + Contacto -->
<section id='contacto' class="py-16 bg-gray-100">
  <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-12 items-center text-center md:text-left">
    <div data-aos="fade-right">
      <div id="map" class="w-full h-[300px] md:h-[400px] rounded-xl shadow-lg border-4 border-orange-500"></div>
    </div>
    <div data-aos="fade-left">
      <div class="bg-[#0b1a2b] text-white rounded-2xl shadow-lg p-6 space-y-4">
        <p class="uppercase text-sm text-gray-300">Sucursal</p>
        <h2 class="text-2xl font-bold">Balmaceda</h2>
        <p class="text-sm text-gray-200">
          Mackenna Nro 768, Balmaceda — Región de Aysén
        </p>
        <div class="border-t border-gray-600 pt-4 space-y-2">
          <p class="text-orange-400 font-semibold text-sm">CONTACTOS</p>
          <p>+56 9 9511 0639</p>
          <p>+56 9 4264 4477</p>
          <p>rentacarencoyhaique@gmail.com</p>
        </div>
        <div class="flex items-start gap-2 text-sm text-gray-300 bg-gray-900 p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" strokeLinejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 15h14M5 19h14" />
          </svg>
          <p>Lunes a Domingo de 09:00 a 17:00.</p>
        </div>
        <a href="#cotizar" class="inline-block bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-xl text-white font-semibold shadow-md">
          Ir a Cotizar →
        </a>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  function setVideo(url) {
    document.getElementById('videoFrame').src = url;
  }
</script>
<script>
  new Swiper('.image-carousel', {
    loop: true,
    spaceBetween: 20,
    slidesPerView: 1,
    autoplay: { delay: 3000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    breakpoints: { 640: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
  });
</script>
<script>
  var map = L.map('map').setView([-45.90998, -71.698627], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
  L.marker([-45.90998, -71.698627]).addTo(map)
    .bindPopup('Rent a Car - Mackenna 768').openPopup();
</script>

<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});
</script>
@endpush

@endsection
