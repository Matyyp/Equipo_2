@extends('tenant.layouts.admin')

@section('title', 'Listado de Ingresos')
@section('page_title', 'Listado de Ingresos')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    /* Solución al conflicto de superposición del overlay */
    #sidebar-overlay {
        pointer-events: none !important;
        background: transparent !important;
    }
/* Estilos para mejorar la apariencia */

/* Estilos para el select y la lista */
#serviceSelect {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    background-color: white;
    cursor: pointer;
}

#serviceSelect option {
    padding: 8px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

#serviceSelect option:hover {
    background-color: #f8f9fa;
}

#selectedServicesList {
    background-color: #f8f9fa;
    border-radius: 4px;
}

.remove-service {
    padding: 0.15rem 0.35rem;
    line-height: 1;
}

#extraServicesTotal {
    font-size: 1.2rem;
    color: #28a745;
}

/* Estilos para los métodos de pago - Versión responsive */
.payment-methods {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.payment-option {
  flex: 1 1 calc(50% - 0.5rem);
  min-width: 120px;
}

.payment-option input[type="radio"] {
  position: absolute;
  opacity: 0;
}

.payment-option label {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  border: 1px solid #dee2e6;
  border-radius: 0.35rem;
  cursor: pointer;
  transition: all 0.2s;
  height: 100%;
  margin-bottom: 0;
}

.payment-option input:checked + label {
  border-color: #4e73df;
  background-color: #f8f9fc;
  box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
}

.payment-icon {
  display: inline-flex;
  width: 24px;
  height: 24px;
  align-items: center;
  justify-content: center;
  margin-right: 0.5rem;
  border-radius: 50%;
}

/* Estilos para pantallas pequeñas */
@media (max-width: 767.98px) {
  .payment-option {
    flex: 1 1 100%;
  }
  
  .payment-option label {
    flex-direction: column;
    text-align: center;
    padding: 0.75rem 0.5rem;
  }
  
  .payment-icon {
    margin-right: 0;
    margin-bottom: 0.5rem;
    width: 32px;
    height: 32px;
  }
}

/* Efectos hover */
.payment-option label:hover {
  background-color: #f8f9fa;
  border-color: #d1d3e2;
}

/* Colores más intensos para mejor visibilidad */
.table-warning {
    background-color: #fff3cd !important;
    border-left: 3px solid #ffc107 !important;
}

.table-danger {
    background-color: #f8d7da !important;
    border-left: 3px solid #dc3545 !important;
}

/* Resaltar texto */
.table.table-warning td,
.table.table-danger td {
    color: #212529;
    font-weight: 500;
}

/* Opcional: hover effects */
.table.table-warning:hover td {
    background-color: #ffe69c !important;
}

.table.table-danger:hover td {
    background-color: #f5b5bd !important;
}
  </style>
  <style>
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
      color:rgb(255, 255, 255) !important;
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
  <div class="card">
    <div class="card-header bg-secondary text-white">
      <div class="d-flex justify-content-between align-items-center flex-wrap w-100">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <i class="fas fa-history mr-2"></i>Listado de Estacionados
        </div>
        <div class="text-end">
          @php
            $bloqueado = !$empresaExiste || !$sucursalExiste;
          @endphp

          @if(!$empresaExiste)
            <div class="d-inline-flex align-items-center gap-2 p-2 mb-2 mb-md-0 rounded" style="background-color: #17a2b8;">
              <i class="fas fa-exclamation-circle mr-2"></i>
              <span class="small">Complete la configuración: <strong>Faltan datos de la empresa</strong></span>
              <a href="{{ route('empresa.index') }}"
                class="btn btn-sm btn-outline-light ml-2" style="white-space: nowrap;">
                <i class="fas fa-building me-1"></i> Registrar empresa
              </a>
            </div>
          @elseif(!$sucursalExiste)
            <div class="d-inline-flex align-items-center gap-2 p-2 mb-2 mb-md-0 rounded" style="background-color: #17a2b8;">
              <i class="fas fa-exclamation-circle mr-2"> </i>
              <span class="small">Complete la configuración: <strong>Falta crear una sucursal</strong></span>
              <a href="{{ route('sucursales.index') }}"
                class="btn btn-sm btn-outline-light ml-2" style="white-space: nowrap;">
                <i class="fas fa-store me-1"></i> Crear sucursal
              </a>
            </div>
          @else
            <a href="{{ route('estacionamiento.create') }}"
              class="btn btn-sm btn-outline-light" style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
              <i class="fas fa-plus me-1"></i> Ingresar vehículo
            </a>
        
            <a href="{{ route('estacionamiento.ticket') }}"
              class="btn btn-sm btn-outline-light" style="background-color: transparent; border: 1px solid currentColor; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px;" class="ml-auto">
              <i class="fas fa-print me-1"></i> Imprimir Tickets
            </a>
          @endif
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table id="parking-table" class="table table-striped table-bordered nowrap w-100">
          <thead>
            <tr>
              @role('SuperAdmin')
                <th>Sucursal</th>
              @endrole
              <th>Nombre</th>
              <th>Patente</th>
              <th>Auto</th>
              <th>Inicio</th>
              <th>Término</th>
              <th>Días</th>
              <th>Incluye lavado</th>
              <th>Precio Servicio</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="extraServicesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title">Servicios Adicionales</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="extraServicesForm">
          @csrf
          <input type="hidden" name="register_id" id="extra-services-register-id">
          
          <!-- Selector de servicios -->
          <div class="form-group">
            <label class="font-weight-bold">Seleccione servicios:</label>
            <select class="form-control" id="serviceSelect" size="5">
              <!-- Opciones se cargarán dinámicamente -->
            </select>
          </div>

          <!-- Lista de servicios seleccionados -->
          <div class="border rounded p-2 mb-3 bg-light">
            <label class="font-weight-bold">Servicios seleccionados:</label>
            <div id="selectedServicesList" style="min-height: 100px; max-height: 200px; overflow-y: auto;">
              <div class="text-muted text-center py-4">No hay servicios seleccionados</div>
            </div>
          </div>

          <!-- Total -->
          <div class="text-right border-top pt-2">
            <span class="font-weight-bold">Total adicional:</span>
            <span id="extraServicesTotal" class="font-weight-bold text-secondary ml-2">$0</span>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="saveExtraServices" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Check-Out -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form id="checkout-form" method="POST">
      @csrf
      <input type="hidden" name="id_parking_register" id="checkout-id">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title" id="checkoutModalLabel">
            <i class="fas fa-door-open mr-2"></i>Confirmar Check-Out
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="row">
            <!-- Sección izquierda - Detalles del servicio -->
            <div class="col-md-8">
              <div class="card mb-4">
                <div class="card-header bg-light">
                  <h6 class="mb-0">
                    <i class="fas fa-list-check mr-2"></i>Detalles del Servicio
                  </h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label class="font-weight-bold">Servicios incluidos:</label>
                    <div class="table-responsive">
                      <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                          <tr>
                            <th>Servicio</th>
                            <th class="text-right">Precio</th>
                            <th class="text-right">Estado</th>
                          </tr>
                        </thead>
                        <tbody id="services-list">
                          <!-- Los servicios se cargarán aquí dinámicamente -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Sección derecha - Método de pago -->
            <div class="col-md-4">
              <div class="card h-100">
                <div class="card-header bg-light">
                  <h6 class="mb-0">
                    <i class="fas fa-credit-card mr-2"></i>Método de Pago
                  </h6>
                </div>
                <div class="card-body">
                  <!-- Total a pagar -->
                  <div class="form-group mb-3">
                    <label class="font-weight-bold text-muted small">TOTAL A PAGAR</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-white font-weight-bold">$</span>
                      </div>
                      <input type="text" 
                             id="checkout-total" 
                             class="form-control border-left-0 font-weight-bold text-black" 
                             readonly
                             value="0">
                    </div>
                  </div>
                  
                  <!-- Métodos de pago -->
                  <div class="mb-3">
                    <label class="font-weight-bold text-muted small mb-2 d-block">SELECCIONE MÉTODO</label>
                    <div class="payment-methods">
                      <!-- Efectivo -->
                      <div class="payment-option">
                        <input type="radio" name="type_payment" id="payment-cash" value="efectivo" checked>
                        <label for="payment-cash" class="d-flex align-items-center">
                          <span class="payment-icon bg-success text-white">
                            <i class="fas fa-money-bill-wave"></i>
                          </span>
                          <span class="font-weight-bold">Efectivo</span>
                        </label>
                      </div>
                      
                      <!-- Tarjeta Crédito -->
                      <div class="payment-option">
                        <input type="radio" name="type_payment" id="payment-credit" value="tarjeta_credito">
                        <label for="payment-credit" class="d-flex align-items-center">
                          <span class="payment-icon bg-primary text-white">
                            <i class="fas fa-credit-card"></i>
                          </span>
                          <span class="font-weight-bold">Crédito</span>
                        </label>
                      </div>
                      
                      <!-- Tarjeta Débito -->
                      <div class="payment-option">
                        <input type="radio" name="type_payment" id="payment-debit" value="tarjeta_debito">
                        <label for="payment-debit" class="d-flex align-items-center">
                          <span class="payment-icon bg-info text-white">
                            <i class="fas fa-credit-card"></i>
                          </span>
                          <span class="font-weight-bold">Débito</span>
                        </label>
                      </div>
                      
                      <!-- Transferencia -->
                      <div class="payment-option">
                        <input type="radio" name="type_payment" id="payment-transfer" value="transferencia">
                        <label for="payment-transfer" class="d-flex align-items-center">
                          <span class="payment-icon bg-warning text-white">
                            <i class="fas fa-exchange-alt"></i>
                          </span>
                          <span class="font-weight-bold">Transferencia</span>
                        </label>
                      </div>
                      
                      <!-- Otro -->
                      <div class="payment-option">
                        <input type="radio" name="type_payment" id="payment-other" value="otro">
                        <label for="payment-other" class="d-flex align-items-center">
                          <span class="payment-icon bg-secondary text-white">
                            <i class="fas fa-question-circle"></i>
                          </span>
                          <span class="font-weight-bold">Otro</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <div id="renew-button-container" class="mr-auto" style="display: none;">
            <button type="button" id="btn-renew" class="btn-primary">
              <i class="fas fa-sync-alt mr-1"></i> Renovar Servicio
            </button>
          </div>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            Confirmar Check-Out
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<!-- Dependencias -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

<!-- CSRF token para AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// Configuración inicial
const userIsSuperAdmin = @json(auth()->check() && auth()->user()->hasRole('SuperAdmin'));
let currentRegisterBranchId = null;
</script>

<script>
$(function () {
    const table = $('#parking-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("estacionamiento.index") }}',
            dataSrc: 'data',
            error: function (xhr, error, thrown) {
                alert(`Error cargando datos: ${xhr.status} – ${thrown}`);
            }
        },
        rowCallback: function(row, data, index) {
            // Solo aplicamos a parking_annual
            if (data.service_type === 'parking_annual' && data.end_date) {
                // Parsear fecha en formato DD-MM-AAAA
                const dateParts = data.end_date.split('-');
                const endDate = new Date(
                    parseInt(dateParts[2], 10),  // año
                    parseInt(dateParts[1], 10) - 1,  // mes (0-based)
                    parseInt(dateParts[0], 10)   // día
                );
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                // Calculamos la diferencia en días
                const diffTime = endDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                // Aplicamos clases según la diferencia
                if (diffDays <= 0) {
                    // Fecha vencida - rojo
                    $(row).addClass('table-danger');
                } else if (diffDays <= 5) {
                    // Fecha próxima a vencer - amarillo
                    $(row).addClass('table-warning');
                }
            }
        },
        columns: [
            @role('SuperAdmin')
                { data: 'branch_name', title: 'Sucursal' },
            @endrole
            { data: 'owner_name' },
            { data: 'patent' },
            { data: 'brand_model' },
            { data: 'start_date' },
            {
    data: 'end_date',
    render: function(date, type, row) {
        const formattedDate = date || '-';
        let reminderButton = '';
        
        // Solo para parking_annual
        if (row.service_type === 'parking_annual' && date) {
            const [day, month, year] = date.split('-');
            const endDate = new Date(`${year}-${month}-${day}`);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const diffDays = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
            
            if (diffDays <= 5) { // Mostrar si faltan 5 días o menos
                const isExpired = diffDays <= 0;
                reminderButton = `
                    <div class="mt-1">
                        <button class="btn btn-xs ${isExpired ? 'btn-danger' : 'btn-warning'} btn-reminder w-100"
                            data-id="${row.id_parking_register}"
                            data-whatsapp="${row.whatsapp_payment_reminder_url}">
                            <i class="fas ${isExpired ? 'fa-exclamation-triangle' : 'fa-bell'} mr-1"></i>
                            ${isExpired ? '¡Vencido!' : `Recordatorio (${diffDays}d)`}
                        </button>
                    </div>
                `;
            }
        }
        
        return `
            <div>
                <div>${formattedDate}</div>
                ${reminderButton}
            </div>
        `;
    }
},
            { data: 'days' },
            { 
                data: 'washed',
                render: function(data) {
                    return data
                    ? '<span class="badge bg-transparent text-success border border-success px-3 py-2 rounded ">Sí</span>'
                    : '<span class="badge bg-transparent text-secondary border border-secondary px-3 py-2 rounded">No</span>';

                }
            },
            {
                data: 'service_price',
                render: function(data, type, row) {
                    const typeMap = {
                        'parking_daily': 'Estacionamiento Diario',
                        'parking_annual': 'Estacionamiento Anual'
                    };

                    const rawValue = (row.service_type || '').toString().trim().toLowerCase();
                    const serviceType = typeMap[rawValue] || 'Estacionamiento Desconocido';

                    return `
                        <div class="text-left">
                            <div>$${data}</div>
                            <span class="badge badge-sm bg-transparent text-success border-success" style="border: 1px solid; padding: 0.25em 0.5em;">
                                ${serviceType}
                            </span>
                        </div>
                    `;
                }
            },
            { 
                data: 'total_formatted',
                render: function(data, type, row) {
                    return `<span class="font-weight-bold">$${data}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(row) {
                    return `
                        <div >
                            <a href="${row.contract_url }" target="_blank" class="btn btn-sm btn-outline-info text-info" data-toggle="tooltip" data-placement="top" title="Generar contrato">
                                <i class="fas fa-file-contract"></i>
                            </a>
                            <a href="/estacionamiento/${row.id_parking_register}/edit" class="btn btn-sm btn-outline-info text-info"
                             data-toggle="tooltip" data-placement="top" title="Editar registro">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-info btn-checkout text-info" data-toggle="tooltip" data-placement="top"
                                title="Check-Out"
                                data-id="${row.id_parking_register}"
                                data-total="${row.total_value}"
                                data-service-type="${row.service_type}"
                                data-row='${JSON.stringify(row)}'>
                                <i class="fas fa-door-open"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-info btn-extra-services text-info" data-toggle="tooltip" data-placement="top"
                                title="Agregar servicios extra"
                                data-id="${row.id_parking_register}"
                                data-row='${JSON.stringify(row)}'>
                                <i class="fas fa-plus-circle"></i>
                            </button>
                            <button 
                                onclick="sendWhatsappContractLink(${row.id_parking_register})" data-toggle="tooltip" data-placement="top"
                                title="Enviar contrato por WhatsApp"
                                class="btn btn-sm btn-outline-info btn-extra-services text-info">
                                <i class="fab fa-whatsapp"></i>
                            </button>


                        </div>
                    `;
                }
            }
        ],
        order: [[4, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    let currentRowData = null;
    let currentRowNode = null;

    // Modal de Checkout
    $('#parking-table').on('click', '.btn-checkout', function () {
        const row = $(this).data('row');
        currentRowData = row;
        currentRowNode = table.row($(this).closest('tr'));

        $('#checkout-form').attr('action', `/estacionamiento/${row.id_parking_register}/checkout`);
        $('#checkout-id').val(row.id_parking_register);

        if (row.service_type === 'parking_annual') {
            $('#renew-button-container').show();
        } else {
            $('#renew-button-container').hide();
        }
        
        $('#checkout-total').val(new Intl.NumberFormat('es-CL', {
            style: 'currency',
            currency: 'CLP'
        }).format(row.total_value).replace('CLP', '').trim());

        renderCheckoutServices(row);

        if (row.washed && !row.washed_done && row.car_wash_service) {
            $('#lavado-container').removeClass('d-none');
            $('#confirmar-lavado-btn').data('id', row.id_parking_register)
                                    .data('price', row.car_wash_service.price);
        } else {
            $('#lavado-container').addClass('d-none');
        }

        $('#checkoutModal').modal('show');
    });

    function renderCheckoutServices(row) {
        let html = '';
        
        // Servicio principal
        html += `
            <tr>
                <td>Estacionamiento (${row.days} días)</td>
                <td class="text-right">$${row.service_price}</td>
                <td class="text-right"></td>
            </tr>
        `;

        // Servicio de lavado si aplica
        if (row.washed && row.car_wash_service) {
            html += `
                <tr class="${row.washed_done ? '' : 'table-warning'}">
                    <td>${row.car_wash_service.name}</td>
                    <td class="text-right">$${row.car_wash_service.price.toLocaleString('es-CL')}</td>
                    <td class="text-right">
                        ${row.washed_done ? '' : `
                            <button type="button" class="btn btn-sm btn-success btn-mark-washed" 
                                data-id="${row.id_parking_register}" 
                                data-price="${row.car_wash_service.price}">
                                <i class="fas fa-check"></i> Confirmar
                            </button>
                        `}
                    </td>
                </tr>
            `;
        }

        // Servicios adicionales
        if (row.addons && row.addons.length > 0) {
            row.addons.forEach(addon => {
                html += `
                    <tr>
                        <td>${addon.name}</td>
                        <td class="text-right">$${addon.price.toLocaleString('es-CL')}</td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-addon" 
                                    data-id="${addon.id_add}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        $('#services-list').html(html);
    }

    // Eliminar servicio adicional - Versión mejorada
    $(document).on('click', '.btn-remove-addon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const btn = $(this);
        const addonId = btn.data('id');
        const registerId = $('#checkout-id').val();
        const tr = btn.closest('tr');
        const priceText = tr.find('td:nth-child(2)').text().replace('$', '').replace(/\./g, '');
        const price = parseFloat(priceText) || 0;


        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: `/estacionamiento/extra/${addonId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                register_id: registerId
            },
            success: function(response) {
                toastr.success(response.message);
                
                // 1. Eliminar visualmente la fila
                tr.fadeOut(300, function() {
                    $(this).remove();
                    
                    // 2. Actualizar el total
                    const currentTotalText = $('#checkout-total').val().replace('$', '').replace(/\./g, '');
                    const currentTotal = parseFloat(currentTotalText) || 0;
                    const newTotal = currentTotal - price;
                    
                    $('#checkout-total').val(
                        new Intl.NumberFormat('es-CL', {
                            style: 'currency',
                            currency: 'CLP'
                        }).format(newTotal)
                        .replace('CLP', '')
                        .trim()
                    );
                    
                    // 3. Actualizar los datos del botón checkout
                    if (currentRowData) {
                        currentRowData.addons = currentRowData.addons.filter(a => a.id_add !== addonId);
                        currentRowData.total_value = newTotal;
                        $(`.btn-checkout[data-id="${registerId}"]`)
                            .data('total', newTotal)
                            .data('row', JSON.stringify(currentRowData));
                    }
                    
                    btn.prop('disabled', false);
                });
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al eliminar servicio');
                btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    });


// Manejar clic en botón de renovación - Versión corregida
$('#btn-renew').click(function(e) {
    e.preventDefault(); // Prevenir el comportamiento por defecto
    e.stopPropagation(); // Evitar que el evento se propague
    
    if (!currentRowData) {
        toastr.error('No se encontraron datos del registro actual');
        return;
    }
    
    const registerId = currentRowData.id_parking_register;
    const paymentMethod = $('input[name="type_payment"]:checked').val();
    
    // Validación del método de pago
    if (!paymentMethod) {
        toastr.error('Debe seleccionar un método de pago');
        return;
    }
    
    Swal.fire({
        title: '¿Renovar servicio anual?',
        text: 'Esta acción extenderá el servicio por 30 días adicionales',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, renovar',
        cancelButtonText: 'Cancelar',
        backdrop: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Procesando...');
            
            $.ajax({
                url: `/estacionamiento/${registerId}/renew`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    payment_method: paymentMethod
                },
                success: function(response) {
                    // Cerrar ambos modales
                    $('#checkoutModal').modal('hide');
                    
                    // Mostrar confirmación
                    Swal.fire({
                        title: '¡Renovación exitosa!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Recargar la tabla
                        $('#parking-table').DataTable().ajax.reload(null, false);
                    });
                },
                error: function(xhr) {
                    let errorMsg = 'Error al renovar el servicio';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        title: 'Error',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> Renovar Servicio');
                }
            });
        }
    });
});

// Asegurarse de que siempre haya un método seleccionado
$('#payment-method-buttons input').on('click', function(e) {
    if ($(this).is(':checked')) {
        $('#payment-method-buttons label').removeClass('active');
        $(this).closest('label').addClass('active');
    } else {
        // Previene que se deseleccione el último botón
        e.preventDefault();
    }
});
$('#payment-method-buttons label:first').addClass('active');
$('#payment-method-buttons input:first').prop('checked', true);
// Confirmar lavado
$(document).on('click', '.btn-mark-washed', function() {
    const btn = $(this);
    const id = btn.data('id');
    const price = parseFloat(btn.data('price')) || 0;

    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: `/carwash/marcar-lavado/${id}`,
        type: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            toastr.success(response.message);

            // 1. Actualizar los datos locales
            if (currentRowData) {
                currentRowData.washed = true;
                currentRowData.washed_done = true; // ← Añade esta línea
                currentRowData.total_value += price;
                
                // Actualizar el botón checkout
                $('.btn-checkout[data-id="'+id+'"]')
                    .data('total', currentRowData.total_value)
                    .data('row', JSON.stringify(currentRowData));
            }

            // 2. Actualizar la vista del modal
            renderCheckoutServices(currentRowData);
            updateCheckoutTotal(currentRowData.total_value);

            // 3. Ocultar el contenedor de lavado pendiente
            $('#lavado-container').addClass('d-none');
            
            // 4. Opcional: recargar la tabla
            table.ajax.reload(null, false);
        },
        error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Error al confirmar lavado');
            btn.prop('disabled', false).html('<i class="fas fa-check"></i> Confirmar');
        }
    });
});


    // Resetear modales al cerrar
    $('#extraServicesModal, #checkoutModal').on('hidden.bs.modal', function() {
        selectedServices = [];
        currentRegisterId = null;
        if ($.fn.select2) {
            $('#serviceSelect').val(null).trigger('change');
        }
    });

    // Manejar cambio de método de pago
    $('#payment-method-buttons').on('change', function() {
        const method = $(this).find('input:checked').val();
        
        // Mostrar/ocultar contenedor de efectivo si es necesario
        if (method === 'efectivo') {
            $('#cash-container').removeClass('d-none');
            $('#cash-received').focus();
        } else {
            $('#cash-container').addClass('d-none');
        }
        
        // Habilitar botón de renovación (siempre habilitado ahora que hay selección forzada)
        $('#btn-renew').prop('disabled', false);
    });
    // Script para servicios extras (se mantiene igual)
    let availableServices = [];
    let selectedServices = [];
    let currentRegisterId = null;

    $('#parking-table').on('click', '.btn-extra-services', function() {
        const rowData = $(this).data('row');
        currentRegisterBranchId = rowData.id_branch;
        currentRegisterId = rowData.id_parking_register;
        selectedServices = JSON.parse(JSON.stringify(rowData.extra_services || []));
        
        loadAvailableServices();
        renderSelectedServices();
        updateTotal();
        $('#extraServicesModal').modal('show');
    });

    function loadAvailableServices() {
        let url = 'estacionamiento/extra-services';
        let params = {};
        
        if (userIsSuperAdmin && currentRegisterBranchId) {
            params.id_branch = currentRegisterBranchId;
        }
        
        $.ajax({
            url: params ? `${url}?${$.param(params)}` : url,
            type: 'GET',
            success: function(response) {
                availableServices = response;
                renderServiceSelect();
            },
            error: function(xhr) {
                console.error('Error cargando servicios:', xhr);
                showError('No se pudieron cargar los servicios');
            }
        });
    }

    function renderServiceSelect() {
        const select = $('#serviceSelect');
        select.empty();
        
        availableServices.forEach(service => {
            select.append(`<option value="${service.id}">${service.name} - $${service.price.toLocaleString('es-CL')}</option>`);
        });
        
        select.off('change').change(function() {
            const serviceId = $(this).val();
            if (!serviceId) return;
            
            const service = availableServices.find(s => s.id == serviceId);
            if (service) {
                selectedServices.push({
                    ...service,
                    uniqueId: Date.now() + Math.random()
                });
                
                renderSelectedServices();
                updateTotal();
                $(this).val('');
            }
        });
    }

    function renderSelectedServices() {
        const container = $('#selectedServicesList');
        container.empty();
        
        if (selectedServices.length === 0) {
            container.append('<div class="text-muted text-center py-4">No hay servicios seleccionados</div>');
            return;
        }
        
        selectedServices.forEach(service => {
            container.append(`
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                    <div>
                        <span>${service.name}</span>
                        <span class="text-primary ml-2">$${service.price.toLocaleString('es-CL')}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-service" 
                            data-uniqueid="${service.uniqueId}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
        });
    }

    $('#selectedServicesList').on('click', '.remove-service', function() {
        const uniqueId = $(this).data('uniqueid');
        selectedServices = selectedServices.filter(s => s.uniqueId != uniqueId);
        renderSelectedServices();
        updateTotal();
    });

    function updateTotal() {
        const total = selectedServices.reduce((sum, service) => sum + service.price, 0);
        $('#extraServicesTotal').text('$' + total.toLocaleString('es-CL'));
    }

    $('#saveExtraServices').click(function() {
        if (!currentRegisterId) return;
        
        const servicesToSave = selectedServices.map(({id, name, price}) => ({id, name, price}));
        
        $.ajax({
            url: `estacionamiento/${currentRegisterId}/update-extra-services`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                extra_services: servicesToSave
            },
            success: function(response) {
                $('#parking-table').DataTable().ajax.reload(null, false);
                $('#extraServicesModal').modal('hide');
                toastr.success('Servicios actualizados correctamente');
            },
            error: function(xhr) {
                toastr.error('Error al guardar servicios');
                console.error('Error:', xhr.responseJSON);
            }
        });
    });
});

// Limpieza del overlay del sidebar (si existe)
document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById('sidebar-overlay');
    if (overlay) {
        overlay.style.display = 'none';
        overlay.remove();
    }

    $('.user-menu > a').on('click', function (e) {
        e.preventDefault();
        $(this).siblings('.dropdown-menu').toggle();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.user-menu').length) {
            $('.user-menu .dropdown-menu').hide();
        }
    });
});
function renderCheckoutServices(rowData) {
    let html = '';
    
    // Servicio principal
    html += `
        <tr>
            <td>Estacionamiento (${row.days} días)</td>
            <td class="text-right">$${row.service_price}</td>
            <td class="text-right"></td>
        </tr>
    `;

    // Servicio de lavado si aplica
    if (row.washed && row.car_wash_service) {
        html += `
            <tr class="${row.washed_done ? '' : 'table-warning'}">
                <td>${row.car_wash_service.name}</td>
                <td class="text-right">$${row.car_wash_service.price.toLocaleString('es-CL')}</td>
                <td class="text-right">
                    ${row.washed_done ? 
                        '<span class="badge badge-success">Confirmado</span>' : 
                        `<button type="button" class="btn btn-sm btn-success btn-mark-washed" 
                            data-id="${row.id_parking_register}" 
                            data-price="${row.car_wash_service.price}">
                            <i class="fas fa-check"></i> Confirmar
                        </button>`
                    }
                </td>
            </tr>
        `;
    }

    // Servicios adicionales
    if (rowData.addons && rowData.addons.length > 0) {
        rowData.addons.forEach(addon => {
            html += `
                <tr>
                    <td>${addon.name}</td>
                    <td class="text-right">$${addon.price.toLocaleString('es-CL')}</td>
                    <td class="text-right">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-addon" 
                                data-id="${addon.id_add}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    $('#services-list').html(html);
}

function updateCheckoutTotal(total) {
    $('#checkout-total').val(
        new Intl.NumberFormat('es-CL', {
            style: 'currency',
            currency: 'CLP'
        }).format(total)
        .replace('CLP', '')
        .trim()
    );
}
$('#parking-table').on('click', '.btn-reminder', function() {
    const whatsappUrl = $(this).data('whatsapp');
    if (whatsappUrl) {
        window.open(whatsappUrl, '_blank');
        // No cambia el estado del botón después de enviar
    } else {
        console.error('No hay URL de WhatsApp para este registro');
    }
});


function sendWhatsappContractLink(id) {
  fetch(`/whatsapp/contract-link/${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.url) {
        window.open(data.url, '_blank');
      } else {
        alert(data.message || 'No se pudo generar el enlace de WhatsApp.');
      }
    })
    .catch(() => alert('Error al generar el enlace.'));
}

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#parking-table').on('draw.dt', function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
});

</script>

@endpush
