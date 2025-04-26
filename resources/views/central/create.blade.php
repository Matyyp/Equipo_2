<x-app-layout>
    <x-slot name="header">Crear Cliente</x-slot>

    <form method="POST" action="{{ route('tenants.store') }}">
        @csrf

        <label>ID del Cliente:</label>
        <input type="text" name="id" class="block w-full border p-2 mb-4" required>

        <label>Dominio:</label>
        <input type="text" name="domain" class="block w-full border p-2 mb-4" required>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Crear</button>
    </form>
</x-app-layout>
