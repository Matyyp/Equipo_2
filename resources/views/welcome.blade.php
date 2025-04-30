@extends('layouts.app2')

@section('content')

<!-- Hero -->
<!-- Swiper Hero Carousel -->
<section class="relative h-[80vh]">
    <div class="swiper h-full">
        <div class="swiper-wrapper">

            <!-- Slide 1: Vehículos -->
            <div class="swiper-slide h-full bg-cover bg-center relative" style="background-image: url({{ asset('img/hero.jpg') }});">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="z-10 relative flex flex-col items-center justify-center h-full text-white text-center px-4">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" data-aos="fade-down">Explora la Patagonia con seguridad y confort</h1>
                    <p class="text-lg sm:text-xl max-w-xl mb-6" data-aos="fade-up">Vehículos preparados para cada desafío del sur de Chile.</p>
                    <a href="#" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 text-sm sm:text-base rounded" data-aos="zoom-in">Ver vehículos</a>
                </div>
            </div>

            <!-- Slide 2: Servicios Adicionales -->
            <div class="swiper-slide h-full bg-cover bg-center relative" style="background-image: url('{{ asset('img/servicios.jpg') }}')">
                <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                <div class="z-10 relative flex flex-col items-center justify-center h-full text-white text-center px-4">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" data-aos="fade-down">Agenda servicios adicionales</h2>
                    <p class="text-lg sm:text-xl max-w-xl mb-6" data-aos="fade-up">
                        Te ofrecemos lavado completo del vehículo y servicio de estacionamiento seguro en Balmaceda.
                    </p>
                    <a href="#" class="bg-orange-500 hover:bg-orange-600 px-6 py-3 text-sm sm:text-base rounded" data-aos="zoom-in">
                        Agenda ahora
                    </a>
                </div>
            </div>

        </div>
        <!-- Pagination -->
        <div class="swiper-pagination absolute bottom-4 left-0 right-0 mx-auto z-20"></div>
        <!-- Navigation Buttons -->
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>

    </div>
</section>




<!-- Vehículos -->
<section class="py-16 px-4 bg-gray-100">
    <h2 class="text-3xl font-bold text-center mb-10">Servicio personalizado en la región de Aysén</h2>
    <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ([
            ['title' => 'Pick Up', 'desc' => 'Camionetas 4x4 listas para cualquier desafío.', 'img' => 'pickup.jpg'],
            ['title' => 'Todo Terreno', 'desc' => 'Perfectas para caminos de ripio y montaña.', 'img' => '4x4.jpg'],
            ['title' => 'Mini Bus', 'desc' => 'Viajes grupales cómodos y seguros.', 'img' => 'minibus.jpg'],
            ['title' => 'SUV', 'desc' => 'Espacio, confort y tecnología.', 'img' => 'suv.jpg'],
        ] as $vehicle)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105" data-aos="fade-up">
            <img src="{{ asset('img/' . $vehicle['img']) }}" alt="{{ $vehicle['title'] }}" class="w-full h-48 object-cover">
            <div class="p-5">
                <h3 class="text-xl font-bold mb-2">{{ $vehicle['title'] }}</h3>
                <p class="text-sm text-gray-600">{{ $vehicle['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Botón centrado -->
    <div class="mt-10 flex justify-center">
        <a href="#cotizar" class="inline-block bg-orange-500 hover:bg-orange-600 transition px-6 py-2 rounded-xl text-white font-semibold shadow-md">
            Cotiza el Vehiculo ideal para tus Vacaciones →
        </a>
    </div>
</section>

<!-- Sección Quiénes Somos con selector de videos -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-stretch">
        
        <!-- Contenedor de video con botones -->
        <div data-aos="fade-right" class="flex flex-col h-full">
            
            <!-- Selector de videos -->
            <div class="flex gap-2 mb-4">
                <button 
                    onclick="setVideo('https://www.youtube.com/embed/k2-LUK_vmOA')" 
                    class="video-btn bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md transition">
                    Video 1
                </button>
                <button 
                    onclick="setVideo('https://www.youtube.com/embed/zleIaEIBs2M')" 
                    class="video-btn bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md transition">
                    Video 2
                </button>
                <button 
                    onclick="setVideo('https://www.youtube.com/embed/Bxo2JkiqG_o')" 
                    class="video-btn bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md transition">
                    Video 3
                </button>
            </div>

            <!-- Video embed -->
            <div class="flex-grow">
                <div class="w-full h-full aspect-video rounded-2xl overflow-hidden shadow-xl border-4 border-orange-500">
                    <iframe 
                        id="videoFrame"
                        src="https://www.youtube.com/embed/VIDEO_ID_1" 
                        title="Video institucional" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen 
                        class="w-full h-full">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Card de contenido -->
        <div data-aos="fade-left" class="flex items-center">
            <div class="bg-[#0b1a2b] text-white rounded-2xl shadow-lg p-8 space-y-5 w-full">
                <p class="uppercase text-sm text-gray-300 tracking-wider">Nuestra Misión</p>
                <h2 class="text-3xl font-bold text-orange-400">¿Quiénes Somos?</h2>
                <p class="text-base text-gray-200 leading-relaxed">
                    Somos tu alternativa para el arriendo de vehículos en tu visita a la Patagonia.
                </p>
                <p class="text-base text-gray-200 leading-relaxed">
                    Nuestro principal objetivo es ofrecer a nuestros clientes un excelente y completo servicio en el arriendo de vehículos. Deseamos acompañarlos en todas las rutas y destinos de la Patagonia. Y qué mejor que con el respaldo de una empresa que conoce la región.
                </p>
                <div>
                    <a href="#cotizar" class="inline-block mt-4 bg-orange-500 hover:bg-orange-600 transition px-6 py-2 rounded-xl text-white font-semibold shadow-md">
                        Conócenos más →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="bg-grey py-20 px-4">
    <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-10"> <br> Estacionamiento y Lavado de Vehículos</h2>

        <div class="grid md:grid-cols-2 gap-10 items-center">
            <!-- Imagen -->
            <div class="overflow-hidden rounded-2xl shadow-xl border-4 border-blue-200" data-aos="fade-right">
                <img src="{{ asset('img/lavado.jpg') }}" alt="Lavado de Vehículo" class="w-full h-full object-cover">
            </div>

            <!-- Card texto -->
            <div data-aos="fade-left">
                <div class="bg-[#0b1a2b]  text-white rounded-2xl shadow-2xl p-6 space-y-4">
                <h3 class="text-2xl font-bold text-orange-600">Servicio de Lavado Completo</h3>
                <p class=" text-lg leading-relaxed">
                    Sabemos lo importante que es mantener tu vehículo en excelente estado. Por eso, ofrecemos lavado exterior e interior con atención a cada detalle.
                </p>
                <p class="text-sm text-gray-400 italic">*Reserva con anticipación para asegurar tu horario preferido.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-grey-100 py-20 px-4">
    <div class="max-w-6xl mx-auto">


        <div class="grid md:grid-cols-2 gap-10 items-center">
            <!-- Card texto -->
            <div data-aos="fade-right">
                <div class="bg-orange-500 text-white rounded-2xl shadow-2xl p-6 space-y-4">
                    <h3 class="text-2xl font-bold text-[#0b1a2b]">Planes de Estacionamiento a tu Medida</h3>
                    <p class=" text-lg leading-relaxed">
                        Contamos con estacionamiento vigilado disponible por día, semana, mes o año. La ubicación estratégica a minutos del aeropuerto lo hace ideal para viajeros frecuentes.
                    </p>
                    <p class="text-sm text-gray-500 italic">
                        *Antes de contratar, es necesario revisar el contrato con nuestro equipo. Te ayudaremos con todo el proceso.
                    </p>
                </div>
            </div>

            <!-- Imagen -->
            <div class="overflow-hidden rounded-2xl shadow-xl border-4 border-orange-300" data-aos="fade-left">
                <img src="{{ asset('img/estacionamiento.jpg') }}" alt="Estacionamiento" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>




<!-- Carrusel de imágenes -->
<section class="py-16 px-4 bg-white">
    <h2 class="text-3xl font-bold text-center mb-10">Explora la Patagonia en imágenes</h2>
    <div class="swiper image-carousel max-w-5xl mx-auto relative">
        <div class="swiper-wrapper">
            @foreach (['paisaje1.jpg', 'paisaje2.jpg', 'paisaje3.jpg'] as $img)
                <div class="swiper-slide">
                    <img src="{{ asset('img/' . $img) }}" alt="Paisaje de la Patagonia" class="rounded-xl shadow-lg w-full h-[300px] object-cover">
                </div>
            @endforeach
        </div>
        <!-- Flechas de navegación -->
        <div class="swiper-button-next text-orange-500"></div>
        <div class="swiper-button-prev text-orange-500"></div>
        <!-- Paginación -->
        <div class="swiper-pagination mt-4"></div>
    </div>
</section>

<!-- Mapa + contacto (estilo tarjeta al lado derecho) -->
<section class="bg-gray-100 py-16 px-4">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center px-4 text-center md:text-left">
        {{-- Mapa a la izquierda --}}
        <div data-aos="fade-right">
            <div id="map" class="w-full h-[300px] md:h-[400px] rounded-xl shadow-lg border-4 border-orange-500"></div>
        </div>

        {{-- Tarjeta informativa a la derecha --}}
        <div data-aos="fade-left">
            <div class="bg-[#0b1a2b] text-white rounded-2xl shadow-lg p-6 space-y-4">
                <p class="uppercase text-sm text-gray-300">Sucursal</p>
                <h2 class="text-2xl font-bold">Balmaceda</h2>
                <p class="text-sm text-gray-200">
                Mackenna Nro 768, Balmaceda, Patagonia Chilena - Región de Aysén                </p>

                <div class="border-t border-gray-600 pt-4 space-y-2">
                    <div>
                        <p class="text-orange-400 font-semibold text-sm">CONTACTOS</p>
                        <p class="text-white">+56 9 9511 0639</p>
                        <p class="text-white">+56 9 4264 4477</p>
                        <p class="text-white">
                        rentacarencoyhaique@gmail.com</p>
                        
                    </div>

                </div>

                <div class="flex items-start gap-2 text-sm text-gray-300 bg-gray-900 p-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 15h14M5 19h14" />
                    </svg>
                    <p>
                        Lunes a Domingo de 09:00 a 17:00. <br>
                    </p>
                </div>

                <a href="#cotizar" class="inline-block bg-orange-500 hover:bg-orange-600 transition px-6 py-2 rounded-xl text-white font-semibold shadow-md">
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
    var imageSwiper = new Swiper('.image-carousel', {
        loop: true,
        spaceBetween: 20,
        slidesPerView: 1,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.image-carousel .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.image-carousel .swiper-button-next',
            prevEl: '.image-carousel .swiper-button-prev',
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
</script>
<script>
    var map = L.map('map').setView([-45.909980, -71.698627], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);
    L.marker([-45.909980, -71.698627]).addTo(map)
        .bindPopup('Rent a Car - Mackenna 768, Balmaceda')
        .openPopup();
</script>
@endpush

@endsection



