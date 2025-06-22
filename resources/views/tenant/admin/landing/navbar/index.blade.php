@extends('tenant.layouts.admin')

@section('title', 'Gestionar Navbar')
@section('page_title', 'Gestionar Navbar')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-bars mr-2"></i>Navbar</div>
      <a href="{{ route('landing.navbar.edit', $navbar) }}"  
      style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto" title="Editar">
        <i class="fas fa-pen me-1"></i> Editar
      </a>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered w-100">
          <tbody>
            <tr>
              <th width="200">Reservas</th>
              <td>
                {!! $navbar->reservations_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->reservations, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Horario</th>
              <td>
                {!! $navbar->schedule_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->schedule, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Correo</th>
              <td>
                {!! $navbar->email_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->email, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Dirección</th>
              <td>
                {!! $navbar->address_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->address, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Servicios</th>
              <td>
                {!! $navbar->services_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->services, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Quiénes Somos</th>
              <td>
                {!! $navbar->about_us_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->about_us, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Contáctanos</th>
              <td>
                {!! $navbar->contact_us_active 
                  ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                  : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                <div class="mt-2 small">{{ Str::limit($navbar->contact_us, 80) }}</div>
              </td>
            </tr>
            <tr>
              <th>Colores</th>
              <td>
                <div class="d-flex flex-wrap gap-3 small">
                  @foreach([
                    'Fondo 1' => $navbar->background_color_1,
                    'Fondo 2' => $navbar->background_color_2,
                    'Botón 1' => $navbar->button_color_1,
                    'Botón 2' => $navbar->button_color_2,
                    'Texto 1' => $navbar->text_color_1,
                    'Texto 2' => $navbar->text_color_2
                  ] as $label => $color)
                    <div class="d-flex align-items-center gap-2 me-4">
                      <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color }};border-radius:50%;border:1px solid #ccc;"></span>
                      {{ $label }}
                    </div>
                  @endforeach
                </div>
              </td>
            </tr>
            <tr>
              <th>Botones</th>
              <td>
                <div class="d-flex flex-column small">
                  <div>
                    {!! $navbar->button_1_active 
                      ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                      : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                    <strong class="ms-1">Botón 1:</strong> {{ Str::limit($navbar->button_1, 60) }}
                  </div>
                  <div class="mt-2">
                    {!! $navbar->button_2_active 
                      ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                      : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                    <strong class="ms-1">Botón 2:</strong> {{ Str::limit($navbar->button_2, 60) }}
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
