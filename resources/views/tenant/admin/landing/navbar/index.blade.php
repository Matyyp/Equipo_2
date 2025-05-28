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
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="navbar-table" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Reservas</th>
              <th>Horario</th>
              <th>Correo</th>
              <th>Dirección</th>
              <th>Servicios</th>
              <th>Quienes Somos</th>
              <th>Contáctanos</th>
              <th>Colores</th>
              <th>Botones</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                @if($navbar->reservations_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->reservations, 50) }}</div>
              </td>

              <td>
                @if($navbar->schedule_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->schedule, 50) }}</div>
              </td>

              <td>
                @if($navbar->email_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->email, 50) }}</div>
              </td>

              <td>
                @if($navbar->address_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->address, 50) }}</div>
              </td>

              <td>
                @if($navbar->services_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->services, 50) }}</div>
              </td>

              <td>
                @if($navbar->about_us_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->about_us, 50) }}</div>
              </td>

              <td>
                @if($navbar->contact_us_active)
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                @else
                  <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                @endif
                <div class="mt-2 small">{{ Str::limit($navbar->contact_us, 50) }}</div>
              </td>

              <td>
                <div class="d-flex flex-column gap-1 small">
                  @foreach([
                    'Fondo 1' => $navbar->background_color_1,
                    'Fondo 2' => $navbar->background_color_2,
                    'Botón 1' => $navbar->button_color_1,
                    'Botón 2' => $navbar->button_color_2,
                    'Texto 1' => $navbar->text_color_1,
                    'Texto 2' => $navbar->text_color_2
                  ] as $label => $color)
                    <div class="d-flex align-items-center gap-2">
                      <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color }};border-radius:50%;border:1px solid #ccc;"></span>
                      {{ $label }}
                    </div>
                  @endforeach
                </div>
              </td>

              <td>
                <div class="d-flex flex-column small">
                  <div>
                    @if($navbar->button_1_active)
                      <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                    @else
                      <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                    @endif
                    <strong class="ms-1">Botón 1:</strong> {{ Str::limit($navbar->button_1, 40) }}
                  </div>
                  <div class="mt-3">
                    @if($navbar->button_2_active)
                      <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                    @else
                      <span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>
                    @endif
                    <strong class="ms-1">Botón 2:</strong> {{ Str::limit($navbar->button_2, 40) }}
                  </div>
                </div>
              </td>

              <td>
                <a href="{{ route('landing.navbar.edit', $navbar) }}" class="btn btn-outline-warning btn-sm text-dark me-1" title="Editar">
                  <i class="fas fa-pen"></i>
                </a>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection


