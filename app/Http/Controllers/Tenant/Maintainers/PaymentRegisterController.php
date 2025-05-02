<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;
use App\Models\PaymentRegister;
use App\Models\Register;
use App\Models\Voucher;
use App\Models\Service;
use App\Models\Car;
use App\Models\Owner;



use Yajra\DataTables\Facades\DataTables;

class PaymentRegisterController extends Controller
{
    /**
     * Listado con DataTables
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = PaymentRecord::with([
                'paymentRecordPayment.voucher',
                'paymentRecordPayment.service.service_parking.parking_register.register_parking_register',
                'paymentRecordPayment.service.service_parking.parking_park' => function ($q) {
                    $q->withTrashed();
                },
                'paymentRecordPayment.service.service_parking.parking_park.park_car.car_belongs.belongs_owner',
            ])
            ->get()
            ->filter(fn($rec) => $rec->paymentRecordPayment)
            ->map(function ($rec) {
                $payment = $rec->paymentRecordPayment;
                $svc      = $payment->service;
                $voucher  = $payment->voucher;

                $pRp      = $svc?->service_parking?->parking_register?->first();
                $register = $pRp?->register_parking_register;
                $idPark   = $register?->id_park;

                $parking  = $svc?->service_parking?->parking_park?->firstWhere('id_park', $idPark);

                $car      = $parking?->park_car?->first();
                $owner    = $car?->car_belongs?->first()?->belongs_owner;

                return [
                    'type_service' => $svc?->type_service ?? '-',
                    'price_net'    => $svc?->price_net ?? 0,
                    'id_voucher'   => $voucher?->id_voucher ?? '-',
                    'owner_name'   => $owner?->name ?? '-',
                    'car_patent'   => $car?->patent ?? '-',
                    'total_value'  => $register?->total_value ?? 0,
                    'payment_id'   => $rec->getKey(),
                ];
            });

            return response()->json(['data' => $rows->values()]);
        }

        return view('tenant.admin.maintainer.payment.index');
    }

    /**
     * Formulario de creación
     */
    public function create()
{
    $q = \App\Models\Register::doesntHave('paymentRecord')
        ->with([
          'service',
          'register_parking_register.park.park_car.car_belongs.belongs_owner',
        ]);

    // Volcamos la consulta que se va a ejecutar:
    dd($q->toSql());

    $registers = $q->get();
    // …
}

    /**
     * Guardar nuevo pago
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_register'   => 'required|exists:registers,id_register',
            'id_voucher'    => 'nullable|exists:vouchers,id_voucher',
            'amount'        => 'required|numeric',
            'type_payment'  => 'required|string|max:255',
            'payment_date'  => 'required|date',
        ]);

        // Crear registro de pago principal
        $register = Register::findOrFail($data['id_register']);
        $paymentRegister = PaymentRegister::create([
            'id_service'   => $register->id_service,
            'id_voucher'   => $data['id_voucher'] ?? null,
            'amount'       => $data['amount'],
            'type_payment' => $data['type_payment'],
            'payment_date' => $data['payment_date'],
        ]);

        // Asociar registro al pago
        PaymentRecord::create([
            'id_payment'  => $paymentRegister->id_payment,
            'id_register' => $register->id_register,
        ]);

        return redirect()
            ->route('payment.index')
            ->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit(PaymentRecord $record)
    {
        $record->load(['paymentRecordPayment', 'paymentRecordPayment.service', 'paymentRecordPayment.voucher']);

        $registers = Register::with(['service', 'parkingRegister.park.park_car.car_belongs.belongs_owner'])->get();
        $vouchers  = Voucher::all();

        return view('tenant.admin.maintainer.payment.edit', compact('record', 'registers', 'vouchers'));
    }

    /**
     * Actualizar pago
     */
    public function update(Request $request, PaymentRecord $record)
    {
        $data = $request->validate([
            'id_register'   => 'required|exists:registers,id_register',
            'id_voucher'    => 'nullable|exists:vouchers,id_voucher',
            'amount'        => 'required|numeric|min:0',
            'type_payment'  => 'required|string|max:255',
            'payment_date'  => 'required|date',
        ]);

        $payment = $record->paymentRecordPayment;
        $payment->update([
            'id_voucher'   => $data['id_voucher'] ?? null,
            'amount'       => $data['amount'],
            'type_payment' => $data['type_payment'],
            'payment_date' => $data['payment_date'],
        ]);

        // Actualizar asociación si cambia el registro
        if ($record->id_register !== $data['id_register']) {
            $record->update(['id_register' => $data['id_register']]);
        }

        return redirect()
            ->route('payment.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    /**
     * Eliminar pago
     */
    public function destroy(PaymentRecord $record)
    {
        // Eliminar pivot y registro de pago principal
        $payment = $record->paymentRecordPayment;
        $record->delete();
        $payment->delete();

        return redirect()
            ->route('payment.index')
            ->with('success', 'Pago eliminado correctamente.');
    }
}
