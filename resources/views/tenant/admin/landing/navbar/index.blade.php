@extends('tenant.layouts.admin')

@section('title', 'Gestionar Navbar')
@section('page_title', 'Gestionar Navbar')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="row w-100 align-items-center">
        <div class="col">
          <i class="fas fa-bars me-2"></i>
          <span>Previsualización del Navbar</span>
        </div>
        <div class="col-auto ms-auto">
          <a href="{{ route('landing.navbar.edit', $navbar) }}"
            style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;">
            <i class="fas fa-pen me-1"></i> Editar
          </a>
        </div>
      </div>
    </div>


    <div class="card-body">
      {{-- ✅ Estilos solo para este bloque --}}
      <style>
        @import url('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
        [x-cloak] { display: none !important; }
      </style>

      <div class="tw-navbar-preview">
        @include('layouts.navbar')
      </div>
    </div>
  </div>
</div>
@endsection
