<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

    {{-- Imagen de fondo con sombra/overlay --}}
    <div class="min-h-screen bg-cover bg-center relative" style="background-image: url('{{ $tenantFunds ?? asset('central/fondo.png') }}');">

        {{-- Sombra oscura encima del fondo --}}
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            {{-- Tarjeta del formulario --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/90 dark:bg-gray-800/80 shadow-lg overflow-hidden sm:rounded-lg backdrop-blur-md">

                {{-- Solo mostrar logo si existe --}}
                @if (!empty($tenantLogo))
                    <div class="flex justify-center mb-4">
                        <a href="/">
                            <img src="{{ $tenantLogo }}" alt="Logo de la empresa" class="h-16 object-contain">
                        </a>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
