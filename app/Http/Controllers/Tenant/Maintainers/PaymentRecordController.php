<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use App\Models\PaymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = PaymentRecord::with([
                'paymentService.service.service_parking.parking_register' => fn($q) => $q->withTrashed(),
                'paymentService.service.service_parking.parking_register.register_parking_register' => fn($q) => $q->withTrashed(),
                'paymentService.service.service_parking.parking_park' => fn($q) => $q->withTrashed(),
                'paymentService.service.service_parking.parking_park.park_car.car_belongs.belongs_owner',
                'paymentVoucher',
            ])
            ->get()
            ->map(function ($record) {
                $payment = $record->paymentService;
                $service = $payment?->service;
                $voucher = $record->paymentVoucher;
    
                $parkingReg = $service?->service_parking?->parking_register?->first();
                $register   = $parkingReg?->register_parking_register;
                $idPark     = $register?->id_park;
                $park       = $service?->service_parking?->parking_park?->firstWhere('id', $idPark);
                $car        = $park?->park_car;
                $owner      = $car?->car_belongs?->first()?->belongs_owner;
    
                return [
                    'id_payment'    => $record->id_payment,
                    'payment_date'  => $record->payment_date,
                    'amount'        => $record->amount,
                    'type_payment'  => $record->type_payment,
                    'voucher_id'    => $voucher?->id_voucher ?? '-',
                    'service_name'  => $service?->name ?? '-',
                    'type_service'  => $service?->type_service ?? '-',
                    'price_net'     => $service?->price_net ?? 0,
                    'car_patent'    => $car?->patent ?? '-',
                    'owner_name'    => $owner?->name ?? '-',
                    'total_value'   => $register?->total_value ?? 0,
                ];
            });
    
            return response()->json(['data' => $rows->values()]);
        }
    
        return view('tenant.admin.maintainer.payment.index');
    }    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentRecord $paymentRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentRecord $paymentRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentRecord $paymentRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentRecord $paymentRecord)
    {
        //
    }
}