<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent a Car Coyhaique</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- AOS Animations -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body class="bg-white text-gray-800">

    @include('components.navbar')

    @yield('content')

    @include('components.footer')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
    @stack('scripts')

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".swiper", {
            loop: true,
            effect: "fade",
            autoplay: {
            delay: 5000,
            },
            pagination: {
            el: ".swiper-pagination",
            clickable: true,
            },
            navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
            },
        });
    </script>


</body>
</html>
