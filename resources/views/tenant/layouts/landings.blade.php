<!DOCTYPE html>
<html lang="es" class="overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "AutoRental",
        "name": "Rent a Car Coyhaique - René Sandoval Pérez",
        "image": "https://rentacarencoyhaique2.cl/img/logo.png",
        "@id": "https://rentacarencoyhaique2.cl",
        "url": "https://rentacarencoyhaique2.cl",
        "priceRange": "$$",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Calle Mackena N°768",
            "addressLocality": "Balmaceda",
            "addressRegion": "Aysén",
            "postalCode": "5950000",
            "addressCountry": "CL"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": -45.5752, 
            "longitude": -72.0662
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday"
            ],
            "opens": "08:00",
            "closes": "22:00"
        },
        "description": "Arriendo de vehículos y custodia privada en Coyhaique. Atendido por su dueño René Sandoval. Seguridad garantizada en Calle Mackena 768."
        }
        </script>
    <title>Rent a car en Coyhaique</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- FontAwesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Forzamos ancho 100% en Swiper para que nunca desborde --}}
    <style>
      .swiper, .swiper-wrapper, .swiper-slide {
        width: 100% !important;
      }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen flex flex-col overflow-x-hidden antialiased text-gray-800">

    @include('layouts.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>

    @stack('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>  
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    });
</script>
</body>
</html>