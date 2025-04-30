@extends('tenant.layouts.admin')

@section('title', 'Listado de Pagos')
@section('page_title', 'Pagos Registrados')

@section('content')
<div class="container mt-5">
    
 

    <a href="{{ route('payment.create') }}" class="btn btn-primary mb-3">
        + Nuevo Pago
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Voucher</th>
                <th>Monto</th>
                <th>Tipo</th>
                <th>Fecha de Pago</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($payments as $p)
            <tr>
                <td>{{ $p->id_payment }}</td>
                <td>{{ $p->service->name ?? '–' }}</td>
                <td>{{ $p->voucher->code ?? '–' }}</td>
                <td>{{ number_format($p->amount, 2, ',', '.') }}</td>
                <td>{{ $p->type_payment }}</td>
                <td>{{ $p->payment_date->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('payment.edit', $p->id_payment) }}"
                       class="btn btn-sm btn-warning">
                       Editar
                    </a>
                  
                    <form action="{{ route('payment.destroy', $p->id_payment) }}"
                          method="POST"
                          style="display:inline"
                          onsubmit="return confirm('¿Seguro que quieres borrar este pago?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </td>
                  
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No hay pagos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $payments->links() }}
</div>
@endsection
{{-- al final de index.blade.php --}}
@push('scripts')
  @if(session('success'))
    <script>
      Swal.fire({
        title: '¡Pago registrado!',
        text: "{{ session('success') }}",
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      });
    </script>
  @endif
@endpush
