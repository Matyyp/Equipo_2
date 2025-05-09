<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta de Pago</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            padding: 10px 20px;
            max-width: 380px;
            margin: auto;
            border: 1px dashed #ccc;
        }

        h2, h4 {
            text-align: center;
            margin-bottom: 10px;
        }

        .section {
            border-top: 1px dashed #999;
            padding-top: 10px;
            margin-top: 10px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .label {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .barcode {
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 15px;
            border-top: 1px dashed #999;
            padding-top: 10px;
        }
    </style>
</head>
<body>
@if($logoPath)
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="{{ $logoPath }}" alt="Logo" style="max-width: 150px;">
    </div>
@else
    <p style="color: red;">Logo no disponible</p>
@endif


    <h2>Boleta de Pago</h2>

    <div class="section center">
        <div>{{ $record->service->branchOffice->name_branch_offices }}</div>
        <div>{{ $record->service->branchOffice->street }}</div>
    </div>

    <div class="section">
        <div class="row">
            <span class="label">Fecha:</span>
            <span>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="row">
            <span class="label">Pago:</span>
            <span>{{ ucfirst($record->type_payment) }}</span>
        </div>
        <div class="row">
            <span class="label">Monto:</span>
            <span>${{ number_format($record->amount, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span class="label">Descuento:</span>
            <span>{{ $record->voucher->discount ?? 0 }}%</span>
        </div>
    </div>

    @if($isParking && $record->parkingRegister)
        <div class="section">
            <h4>Detalle Estacionamiento</h4>
            <div class="row">
                <span class="label">Días:</span>
                <span>{{ $record->parkingRegister->days }}</span>
            </div>
            <div class="row">
                <span class="label">KM Entrada:</span>
                <span>{{ $record->parkingRegister->arrival_km }} km</span>
            </div>
            <div class="row">
                <span class="label">KM Salida:</span>
                <span>{{ $record->parkingRegister->km_exit }} km</span>
            </div>
        </div>
    @endif

    <div class="barcode center">
        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($record->voucher->code, 'C128') }}" alt="Código de barras">
        <div>{{ $record->voucher->code }}</div>
    </div>

    <div class="footer">
        Gracias por su preferencia
    </div>

</body>
</html>
