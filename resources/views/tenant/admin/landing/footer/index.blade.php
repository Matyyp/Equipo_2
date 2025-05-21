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
              <th>Contacto activo</th>
              <th>Redes</th>
              <th>Redes activo</th>
              <th>Fondo</th>
              <th>Texto 1</th>
              <th>Texto 2</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach($footers as $footer)
              <tr>
                <td>{{ $footer->copyright }}</td>
                <td>{{ $footer->contact_text }}</td>
                <td class="text-center">{{ $footer->contact_active ? 'Sí' : 'No' }}</td>
                <td>{{ $footer->social_text }}</td>
                <td class="text-center">{{ $footer->social_active ? 'Sí' : 'No' }}</td>

                {{-- Color: fondo --}}
                <td class="text-center">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $footer->background_color }}; border: 1px solid #ccc;"></span>
                  </div>
                </td>

                {{-- Color: texto 1 --}}
                <td class="text-center">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $footer->text_color_1 }}; border: 1px solid #ccc;"></span>
                  </div>
                </td>

                {{-- Color: texto 2 --}}
                <td class="text-center">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $footer->text_color_2 }}; border: 1px solid #ccc;"></span>
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
