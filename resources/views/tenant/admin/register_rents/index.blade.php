@extends('tenant.layouts.admin')

@section('title','Registros de Arriendo')
@section('page_title','Listado de Registros de Arriendo')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css"/>
<style>
  #rents-table th, #rents-table td {
    vertical-align: middle;
    white-space: nowrap;
  }
  .card-header i {
    margin-right: 8px;
  }
  table.dataTable td,
  table.dataTable th {
    border: none !important;
  }
  table.dataTable tbody tr {
    border: none !important;
  }
  table.dataTable {
    border-top: 2px solid #dee2e6;
    border-bottom: 2px solid #dee2e6;
  }
  .dataTables_paginate .pagination .page-item.active a.page-link {
    background-color: #17a2b8 !important;
    color: #fff !important;
    border-color: #17a2b8 !important;
  }
  .dataTables_paginate .pagination .page-item .page-link {
    background-color: #eeeeee;
    color: #17a2b8 !important;
    border-color: #eeeeee;
  }
  .btn-outline-info.text-info:hover,
  .btn-outline-info.text-info:focus {
    color: #fff !important;
  }
</style>
@endpush

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card ">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <div>
          <i class="fas fa-file-signature"></i> Registros de Arriendo
          <a href="{{ route('registro-renta.create') }}"
            style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
            <i class="fas fa-plus mr-1"></i> Ingresar un arriendo
          </a>
        </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="rents-table" class="table table-striped w-100">
          <thead>
            <tr>
              <th>RUT</th>
              <th>Cliente</th>
              <th>Auto</th>
              <th>Sucursal</th>
              <th>Desde</th>
              <th>Hasta</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Reseña -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('user_ratings.store') }}">
      @csrf
      <input type="hidden" name="register_rent_id" id="rating_rent_id">
      <input type="hidden" name="stars" id="selected-stars" value="0">
      
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title" id="ratingModalLabel">Añadir Reseña</h5>
          <button type="button" class="btn-close text-white" data-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">

          <!-- Estrellas como íconos -->
          <div class="form-group">
            <label>Estrellas</label>
            <div id="star-rating" class="mb-2">
              @for ($i = 1; $i <= 5; $i++)
                <i class="fas fa-star text-muted star-icon" data-value="{{ $i }}" style="cursor:pointer; font-size: 1.5rem;"></i>
              @endfor
            </div>
          </div>

          <!-- Criterio (solo se muestra si estrellas < 5) -->
          <div class="form-group mt-2 d-none" id="criterio-group">
            <label>Criterio</label>
            <select name="criterio" class="form-control" id="criterio-select">
              <option value="">Seleccione un criterio</option>
              <option value="Impuntual">Impuntual</option>
              <option value="Sin respeto">Sin respeto</option>
              <option value="Mal uso del auto">Mal uso del auto</option>
              <option value="Otro">Otro</option>
            </select>
          </div>

          <!-- Comentario -->
          <div class="form-group mt-2">
            <label>Comentario</label>
            <textarea name="comentario" class="form-control" rows="3"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Reseña</button>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

<script>
$(function(){
  $('#rents-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{!! route("registro_renta.data") !!}',
    columns: [
      { data: 'client_rut', name: 'client_rut' },
      { data: 'client_name', name: 'client_name' },
      { data: 'auto', name: 'rentalCar.brand.name_brand' },
      { data: 'sucursal', name: 'rentalCar.branchOffice.name_branch_offices' },
      { data: 'start_date', name: 'start_date' },
      { data: 'end_date', name: 'end_date' },
      { data: 'acciones', name: 'acciones', orderable: false, searchable: false, className: 'text-center' },
    ],
    order: [[4, 'desc']],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    responsive: true,
  });
});
</script>

<script>
$(document).on('click', 'button[data-target="#ratingModal"]', function () {
  const rentId = $(this).data('id');
  $('#rating_rent_id').val(rentId);
});
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-icon');
    const criterioGroup = document.getElementById('criterio-group');
    const starsInput = document.getElementById('selected-stars');

    stars.forEach(star => {
      star.addEventListener('click', function () {
        const rating = parseInt(this.getAttribute('data-value'));

        // Marcar estrellas
        stars.forEach(s => {
          s.classList.remove('text-warning');
          s.classList.add('text-muted');
        });
        for (let i = 0; i < rating; i++) {
          stars[i].classList.remove('text-muted');
          stars[i].classList.add('text-warning');
        }

        // Actualizar input hidden
        starsInput.value = rating;

        // Mostrar criterio si estrellas < 5
        if (rating < 5) {
          criterioGroup.classList.remove('d-none');
        } else {
          criterioGroup.classList.add('d-none');
          document.getElementById('criterio-select').value = ''; // Limpiar criterio
        }
      });
    });

    // Al abrir modal, reiniciar selección
    $('#ratingModal').on('show.bs.modal', function (event) {
      stars.forEach(s => s.classList.remove('text-warning'));
      stars.forEach(s => s.classList.add('text-muted'));
      starsInput.value = 0;
      criterioGroup.classList.add('d-none');
    });
  });
</script>

@endpush
