<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use App\Models\PaymentRecord;
use App\Models\ParkingRegister;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Stancl\Tenancy\Facades\Tenancy;

class PaymentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (! $request->ajax()) {
            return view('tenant.admin.maintainer.payment.index');
        }
    
        $rows = PaymentRecord::with('paymentVoucher')->get();
    
        $mapped = $rows->map(function ($payment) {
            $parking = ParkingRegister::with([
                'park' => fn($q) => $q->with([
                    'park_car.car_belongs.belongs_owner',
                    'service'
                ])
            ])->find($payment->id_parking_register);
            
    
            $park    = $parking?->park;
            $car     = $park?->park_car;
            $owner   = $car?->car_belongs->first()?->belongs_owner;
            $service = $park?->service;

            if (!$service) {
                return null;
            }
    
            return [
                'id_payment'   => $payment->id_payment,
                'payment_date' => $payment->payment_date,
                'amount'       => $payment->amount,
                'type_payment' => $payment->type_payment,
                'service_name' => $service?->name ?? 'N/D',
                'price_net'    => $service?->price_net ?? 'N/D',
                'car_patent'   => $car?->patent ?? 'N/D',
                'owner_name'   => $owner?->name ?? 'N/D',
                'total_value'  => $parking?->total_value ?? 'N/D',
            ];
        })->filter()->values();
    
        return response()->json(['data' => $mapped]);
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

    public function downloadPdf($id)
    {
        $record = PaymentRecord::with([
            'voucher',
            'service.branchOffice.business',
            'parkingRegister'
        ])->findOrFail($id);

        $isParking = in_array($record->service->type_service, [
            'parking_daily',
            'parking_annual',
        ]);

        $logo = Business::value('logo');
        $logoPath = storage_path('app/public/' . $logo);
        $logoBase64 = 'data:'.mime_content_type($logoPath).';base64,'.base64_encode(file_get_contents($logoPath));

        $pdf = Pdf::loadView('pdf.payment_record', [
            'record'     => $record,
            'isParking'  => $isParking,
            'logoPath'   => $logoBase64 ,
        ]);

        return $pdf->download('boleta_pago.pdf');
    }



}