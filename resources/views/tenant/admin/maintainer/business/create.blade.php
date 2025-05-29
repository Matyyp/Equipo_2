@extends('tenant.layouts.admin')

@section('title', 'Ingresar Negocio')
@section('page_title', 'Ingresar Nuevo Negocio')

@section('content')
<div class="container-fluid">
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <div><i class="fas fa-building mr-2"></i> Formulario de Registro de Negocio</div>
    </div>

    <div class="card-body">
      {{-- Validación de errores --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <strong>Ups!</strong> Hay algunos problemas con tu entrada:
          <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Formulario --}}
      <form action="{{ route('empresa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
          <label for="name_business" class="form-label">Nombre de la Empresa</label>
          <input type="text" name="name_business" id="name_business"
                 class="form-control @error('name_business') is-invalid @enderror"
                 value="{{ old('name_business') }}" required>
          @error('name_business')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-4">
          <label for="logo" class="form-label">Logo</label>
          <input type="file" name="logo" id="logo" accept="image/*"
                 class="form-control @error('logo') is-invalid @enderror">
          @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group mb-4">
          <label for="funds" class="form-label">Fondo de pantalla del login (opcional)</label>
          <input type="file" name="funds" id="funds" accept="image/*"
                class="form-control @error('funds') is-invalid @enderror">
          @error('funds')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>


        {{-- Botones --}}
        <div class="form-group row justify-content-end">
          <div class="col-auto">
            <a href="{{ route('empresa.index') }}" class="btn btn-secondary me-1">
              Cancelar
            </a>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">
              Guardar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('logo')?.addEventListener('change', function (e) {
    const preview = document.createElement('img');
    preview.className = 'img-thumbnail mt-2';
    preview.style.maxWidth = '150px';
    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        e.target.parentElement.appendChild(preview);
    }
});

document.getElementById('funds')?.addEventListener('change', function (e) {
    const preview = document.createElement('img');
    preview.className = 'img-thumbnail mt-2';
    preview.style.maxWidth = '300px';
    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        e.target.parentElement.appendChild(preview);
    }
});
</script>
@endsection
