@extends('tenant.layouts.admin')
@section('title', 'Gestionar Footer')
@section('page_title', 'Gestionar Footer')

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-shoe-prints mr-2"></i>Footers</div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th>Copyright</th>
              <th>Contacto</th>
              <th>Redes</th>
              <th>Colores</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($footers as $footer)
              <tr>
                <td>
                  <span class="badge bg-success">Activo</span>
                  <div class="mt-2 small">{{ Str::limit($footer->copyright, 50) }}</div>
                </td>
                <td>
                  @if($footer->contact_active)
                    <span class="badge bg-success">Activo</span>
                  @else
                    <span class="badge bg-secondary">Inactivo</span>
                  @endif
                  <div class="mt-2 small">{{ Str::limit($footer->contact_text, 50) }}</div>
                </td>
                <td>
                  @if($footer->social_active)
                    <span class="badge bg-success">Activo</span>
                  @else
                    <span class="badge bg-secondary">Inactivo</span>
                  @endif
                  <div class="mt-2 small">{{ Str::limit($footer->social_text, 50) }}</div>
                </td>

                <td>
                  <div class="d-flex flex-column gap-1 small">
                    @foreach([
                      'Fondo' => $footer->background_color,
                      'Texto 1' => $footer->text_color_1,
                      'Texto 2' => $footer->text_color_2
                    ] as $label => $color)
                      <div class="d-flex align-items-center gap-2">
                        <span style="display:inline-block;width:15px;height:15px;background-color:{{ $color }};border-radius:50%;border:1px solid #ccc;"></span>
                        {{ $label }}
                      </div>
                    @endforeach
                  </div>
                </td>

                <td class="text-center">
                  <a href="{{ route('landing.footer.edit', $footer->id) }}" class="btn btn-outline-secondary btn-sm text-dark me-1" title="Editar">
                    <i class="fas fa-pen"></i>
                  </a>

                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
