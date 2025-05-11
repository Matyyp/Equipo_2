@extends('central.layouts.central')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Clientes') }}
    </h2>
@endsection

@section('content')
    <a href="{{ route('tenants.create') }}" class="text-blue-500">+ Nuevo Cliente</a>

    <ul class="mt-4">
        @foreach ($tenants as $tenant)
            <li>
                <strong>ID:</strong> {{ $tenant->id }}<br>
                <strong>Dominio:</strong> {{ $tenant->domains->first()?->domain }}

                @if($tenant->domains->first())
                    <a href="//{{ $tenant->domains->first()->domain }}" class="text-green-500 ml-4" target="_blank">Sitio web</a>
                @endif

                <a href="{{ route('tenants.edit', $tenant) }}" class="text-blue-500 ml-4">Editar</a>

                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500 ml-2">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
