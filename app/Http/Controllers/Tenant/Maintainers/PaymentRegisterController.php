<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRegister;
use App\Models\Service;
use App\Models\Voucher;


class PaymentRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Trae los registros junto con servicio y voucher relacionados
    $payments = PaymentRegister::with(['service', 'voucher'])
        ->orderBy('payment_date', 'desc')
        ->paginate(10);

    // Trae todos los vouchers (o aplica el filtro que necesites)
    $vouchers = Voucher::all();

    // Pasa ambas variables a la vista
    return view(
        'tenant.admin.maintainer.payment.index',
        compact('payments', 'vouchers')
    );
}


    /**
     * Mostrar formulario de creación de un nuevo pago.
     */
    public function create()
{
    // Sólo servicios SIN ningún PaymentRegister asociado
    $services = Service::whereDoesntHave('paymentRegisters')->get();

    // Sólo vouchers SIN ningún PaymentRegister asociado
    $vouchers = Voucher::whereDoesntHave('paymentRegisters')->get();

    // Enviamos ambas colecciones a la vista
    return view(
        'tenant.admin.maintainer.payment.create',
        compact('services', 'vouchers')
    );
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'id_service'   => 'required|integer|exists:services,id_service',
        'amount'       => 'required|numeric',
        'type_payment' => 'required|string|max:255',
        'payment_date' => 'required|date',
        'id_voucher'   => 'nullable|integer|exists:vouchers,id_voucher',
    ]);

//    dd($data);   // <— aquí verás si trae 'id_voucher' o no

    PaymentRegister::create($data);
    return redirect()->route('payment.index')
                     ->with('success','Pago registrado correctamente.');
}



    /**
     * Display the specified resource.
     */
    public function show(PaymentRegister $PaymentRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentRegister $payment)
    {
        $payment->load('service');  // si quieres la relación
        return view('tenant.admin.maintainer.payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentRegister $payment)
    {
        $data = $request->validate([
            'amount'       => 'required|numeric',
            'type_payment' => 'required|string|max:255',
            'payment_date' => 'required|date',
        ]);

        $payment->update($data);

        return redirect()
            ->route('payment.index')
            ->with('success', 'Pago actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentRegister $payment)
{
        $payment->delete();

        return redirect()
            ->route('payment.index')
            ->with('success', 'Pago eliminado correctamente.');
}
}