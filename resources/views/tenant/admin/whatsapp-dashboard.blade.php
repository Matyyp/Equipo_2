@extends('layouts.admin')

@section('content')

<h2>Configuración WhatsApp</h2>

@if(session('msg'))
    <div style="background:#d4ffd4; padding:10px; border-radius:6px;">
        {{ session('msg') }}
    </div>
@endif

{{-- Estado actual --}}
@if($status['conectado'])
    <div style="padding: 20px; background: #c8ffd3; border-radius: 8px; margin-bottom: 20px;">
        <h3>WhatsApp conectado</h3>
        <p><strong>Número:</strong> +{{ $status['numero'] }}</p>
        <p><strong>Estado:</strong> {{ $status['estado'] }}</p>

        {{-- Botón logout --}}
        <form method="POST" action="{{ route('whatsapp.logout') }}">
            @csrf
            <button class="btn btn-danger mt-2">Cerrar sesión y reconectar</button>
        </form>
    </div>
@else
    <div style="padding: 20px; background: #ffd4d4; border-radius: 8px; margin-bottom:20px;">
        <h3>WhatsApp desconectado</h3>
        <p>Escanee el siguiente QR:</p>

        @if(isset($qr['qr']))
            <img src="data:image/png;base64,{{ $qr['qr'] }}" style="width:250px;">
        @else
            <p>No se pudo obtener QR. Recargue la página.</p>
        @endif
    </div>
@endif

<hr>

{{-- Formulario para enviar PDF --}}
<h3>Enviar PDF</h3>

<form method="POST" action="{{ route('whatsapp.send') }}">
    @csrf

    <label>Número destino:</label>
    <input type="text" name="numero" class="form-control" placeholder="56912345678" required>

    <label class="mt-2">URL del PDF:</label>
    <input type="text" name="archivo" class="form-control" placeholder="https://miweb.com/docs/archivo.pdf" required>

    <button class="btn btn-success mt-3">Enviar PDF</button>
</form>

@endsection
