
<script src="https://unpkg.com/lucide@latest"></script>

<footer class="bg-gray-900 text-white py-8">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between gap-6">
        <div>
            <img src="{{ asset('img/logo.png') }}" class="h-12 mb-2" alt="Logo Footer">
            <p class="text-sm">Explora la Patagonia con seguridad y confort. © {{ date('Y') }}</p>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Contacto</h3>
            <ul class="text-sm space-y-1">
                <li class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="text-orange-500 w-4 h-4"></i>
                    Mackenna Nro 768, Balmaceda
                </li>
                <li class="flex items-center gap-2">
                    <i data-lucide="phone" class="text-orange-500 w-4 h-4"></i>
                    +56 9 9811 0639 / +56 9 4246 4477
                </li>
                <li class="flex items-center gap-2">
                    <i data-lucide="mail" class="text-orange-500 w-4 h-4"></i>
                    rentacarencoyhaique@gmail.com
                </li>
            </ul>
        </div>
        <div>
            <h3 class="font-semibold mb-2">Redes Sociales</h3>
            <ul class="text-sm space-y-1">
                <li class="flex items-center gap-2">
                    <i data-lucide="facebook" class="text-orange-500 w-4 h-4"></i>
                    <a href="#" class="hover:text-orange-400">Facebook</a>
                </li>
                <li class="flex items-center gap-2">
                    <i data-lucide="instagram" class="text-orange-500 w-4 h-4"></i>
                    <a href="#" class="hover:text-orange-400">Instagram</a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<!-- Inicializar los iconos -->
<script>
    lucide.createIcons();
</script>