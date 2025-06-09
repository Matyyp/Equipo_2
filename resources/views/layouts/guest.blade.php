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
            {{-- Contenedor principal --}}
            <div class="w-full max-w-5xl px-4 sm:px-6 py-8">
                <div class="flex flex-col lg:flex-row bg-white/90 dark:bg-gray-800/80 shadow-lg rounded-lg backdrop-blur-md overflow-hidden">
                    
                    {{-- Sección del Logo --}}
                    @if (!empty($tenantLogo))
                        <div class="w-full lg:w-2/5 p-8 flex items-center justify-center relative">
                            {{-- Línea divisoria --}}
                            <div class="absolute bottom-0 left-0 right-0 h-px bg-gray-300 lg:hidden"></div>
                            <div class="absolute right-0 top-0 bottom-0 w-px bg-gray-300 hidden lg:block"></div>
                            
                            <div class="w-full flex justify-center">
                                <a href="/" class="block max-w-xs w-full">
                                    <img src="{{ $tenantLogo }}" alt="Logo de la empresa" 
                                        class="w-full h-auto max-h-64 object-contain object-center">
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Sección del Formulario --}}
                    <div class="@if(!empty($tenantLogo)) w-full lg:w-3/5 @else w-full @endif p-8 sm:p-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>