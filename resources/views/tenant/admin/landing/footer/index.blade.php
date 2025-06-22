@extends('tenant.layouts.admin')
@section('title', 'Gestionar Footer')
@section('page_title', 'Gestionar Footer')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @foreach($footers as $footer)
    <div class="card mb-4">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <div><i class="fas fa-shoe-prints mr-2"></i>Footer</div>
        <a href="{{ route('landing.footer.edit', $footer->id) }}"  
        style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto"title="Editar">
          <i class="fas fa-pen me-1"></i> Editar
        </a>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered w-100">
            <tbody>
              <tr>
                <th width="200">Copyright</th>
                <td>
                  <span class="border border-success text-success px-2 py-1 rounded">Activo</span>
                  <div class="mt-2 small">{{ Str::limit($footer->copyright, 100) }}</div>
                </td>
              </tr>

              <tr>
                <th>Contacto</th>
                <td>
                  {!! $footer->contact_active
                      ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                      : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                  <div class="mt-2 small">{{ Str::limit($footer->contact_text, 100) }}</div>
                </td>
              </tr>

              <tr>
                <th>Redes Sociales</th>
                <td>
                  {!! $footer->social_active
                      ? '<span class="border border-success text-success px-2 py-1 rounded">Activo</span>'
                      : '<span class="border border-dark text-grey px-2 py-1 rounded">Inactivo</span>' !!}
                  <div class="mt-2 small">{{ Str::limit($footer->social_text, 100) }}</div>
                </td>
              </tr>

              <tr>
                <th>Colores</th>
                <td>
                  <div class="d-flex flex-wrap gap-3 small">
                    @foreach([
                      'Fondo' => $footer->background_color,
                      'Texto 1' => $footer->text_color_1,
                      'Texto 2' => $footer->text_color_2
                    ] as $label => $color)
                      <div class="d-flex align-items-center gap-2 me-4">
                        <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color }};border-radius:50%;border:1px solid #ccc;"></span>
                        {{ $label }}
                      </div>
                    @endforeach
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection
