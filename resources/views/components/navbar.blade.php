<div class="hidden lg:flex bg-black text-gray-200 text-sm px-6 py-2 flex-wrap justify-around items-center font-light">
    <div class="flex flex-wrap gap-6 items-center text-[15px]">

        <!-- Reservas -->
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2 8.5C2 6 4 4 6.5 4S11 6 11 8.5 9 13 6.5 13 2 11 2 8.5Zm0 0v6a2 2 0 0 0 2 2h1"/>
            </svg>
            <span><span class="text-orange-400 font-semibold">Reservas:</span> +56 9 9511 0639 / +56 9 4264 4477</span>
        </div>

        <!-- Horario -->
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 6v6l4 2" />
            </svg>
            <span><span class="text-orange-400 font-semibold">Horario:</span> Lunes a Domingo 09:00 - 17:00</span>
        </div>

        <!-- Correo -->
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M4 4h16v16H4z" />
                <path d="M4 4l8 8 8-8" />
            </svg>
            <span><span class="text-orange-400 font-semibold">Correo:</span> rentacarencoyhaique@gmail.com</span>
        </div>

        <!-- Dirección -->
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Z" />
                <circle cx="12" cy="9" r="2.5" />
            </svg>
            <span><span class="text-orange-400 font-semibold">Dirección:</span> 
Mackena Nro 768
Balmaceda</span>
        </div>
    </div>
</div>

<nav class="bg-gray-900 text-white px-6 py-4 relative z-50">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Rent a Car" class="h-10">
        </div>

        <div class="md:hidden">
            <button id="menu-toggle" class="focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <ul
            id="menu"
            class="hidden md:flex absolute md:static
                top-full md:top-auto left-0 w-full md:w-auto
                bg-gray-900 md:bg-transparent
                flex-col md:flex-row gap-0 md:gap-8
                z-40 transition-all duration-200"
        >
            {{-- Servicios deshabilitado --}}
            <li class="w-full md:w-auto">
                <button
                    disabled
                    class="block w-full text-left px-4 py-2
                        text-gray-500 cursor-not-allowed
                        bg-gray-800 md:bg-transparent
                        rounded-none md:rounded"
                    title="Próximamente"
                >
                    Servicios
                </button>
            </li>

            {{-- Quiénes somos --}}
            <li class="w-full md:w-auto">
                <a
                    href="#quienes-somos"
                    class="block w-full text-left px-4 py-2
                        hover:text-orange-400"
                >
                    Quiénes somos
                </a>
            </li>

            {{-- Contáctanos --}}
            <li class="w-full md:w-auto">
                <a
                    href="#contacto"
                    class="block w-full text-left px-4 py-2
                        hover:text-orange-400"
                >
                    Contáctanos
                </a>
            </li>

            {{-- Opciones responsive para guest --}}
            @guest
                <li class="w-full md:hidden">
                    <a
                        href="{{ route('register') }}"
                        class="block w-full px-4 py-2
                            bg-white text-black rounded
                            hover:bg-orange-400"
                    >
                        Únetenos
                    </a>
                </li>
                <li class="w-full md:hidden">
                    <a
                        href="{{ route('login') }}"
                        class="block w-full px-4 py-2
                            bg-orange-500 rounded
                            hover:bg-orange-600 text-center mt-4"
                    >
                        Iniciar sesión
                    </a>
                </li>
            @else
                {{-- Admin Panel en móvil --}}
                @can('admin.panel.access')
                <li class="w-full md:hidden">
                    <a
                        href="{{ route('dashboard') }}"
                        class="block w-full px-4 py-2
                            bg-gray-700 rounded
                            hover:bg-gray-600 text-center"
                    >
                        Admin Panel
                    </a>
                </li>
                @endcan

                {{-- Cerrar sesión en móvil --}}
                <li class="w-full md:hidden">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="block w-full text-center px-4 py-2
                                bg-red-600 rounded
                                hover:bg-red-700 mt-4"
                        >
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            @endguest
        </ul>


        <div class="hidden md:flex gap-3 items-center">
            {{-- Para pantallas md+ --}}  
            @guest
                <a href="{{ route('register') }}" class="bg-white text-black px-4 py-1 rounded hover:bg-orange-400">Únetenos</a>
                <a href="{{ route('login') }}" class="bg-orange-500 px-4 py-1 rounded hover:bg-orange-600">Iniciar sesión</a>
            @else
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-1 hover:text-orange-400">
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="open" x-transition class="absolute right-0 mt-2 bg-white text-black shadow-lg rounded-md overflow-hidden z-50 w-40">
                        @can('admin.panel.access')
                            <li>
                                <button
                                    type="button"
                                    onclick="window.location='{{ route('dashboard') }}'"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-200">
                                    Admin Panel
                                </button>
                            </li>
                        @endcan
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-200">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</nav>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    });
</script>
