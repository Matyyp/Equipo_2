<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Arriendo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .linea-gruesa {
            height: 4px;
            background-color: #000;
            width: 100%;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        td {
            border: 2px solid black;
            padding: 6px;
            vertical-align: top;
        }

        .espaciado-horizontal {
            padding-left: 10px;
            padding-right: 10px;
            white-space: nowrap;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
        }

        .titulo h2 {
            margin: 0;
            font-size: 24px;
        }

        .footer {
            margin-top: 60px;
            font-size: 12px;
        }

        .firmas {
            margin-top: 60px;
            display: flex;
            justify-content: space-around;
            font-size: 12px;
        }

        .firma-box {
            width: 40%;
            text-align: center;
        }

        .firma-box .line {
            border-top: 1px solid black;
            margin-top: 40px;
        }

        .logo-top {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-top img {
            height: 100px;
        }

        .page-break {
            page-break-before: always;
        }

        .seccion-titulo {
            font-weight: bold;
            margin: 10px 0;
            font-size: 13px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="logo-top">
    <img src="{{ $url_logo }}" alt="Logo Empresa">
</div>

<div class="titulo">
    <h2>CONTRATO DE ARRIENDO VEHÍCULO</h2>
</div>

<div class="linea-gruesa"></div>

<!-- Sección: Datos del Vehículo -->
<div class="seccion-titulo">Datos del Vehículo</div>

<table>
    <tr>
        <td class="espaciado-horizontal">MARCA</td>
        <td>{{ $marca }}</td>
        <td class="espaciado-horizontal">MODELO</td>
        <td>{{ $modelo }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">KM SALIDA</td>
        <td>{{ $km_exit }}</td>
        <td class="espaciado-horizontal">COMBUSTIBLE SALIDA</td>
        <td>{{ $combustible }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">GARANTÍA</td>
        <td>${{ number_format($garantia, 0, ',', '.') }}</td>
        <td class="espaciado-horizontal">PAGO TOTAL</td>
        <td>${{ number_format($pago, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">INICIO</td>
        <td>{{ \Carbon\Carbon::parse($inicio)->format('d-m-Y') }}</td>
        <td class="espaciado-horizontal">TÉRMINO</td>
        <td>{{ \Carbon\Carbon::parse($termino)->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">OBSERVACIONES</td>
        <td colspan="3">{{ $observacion }}</td>
    </tr>
</table>

<div class="linea-gruesa"></div>

<!-- Sección: Datos del Cliente -->
<div class="seccion-titulo">Datos del Cliente</div>

<table>
    <tr>
        <td class="espaciado-horizontal">NOMBRE CLIENTE</td>
        <td colspan="3">{{ $nombre }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">RUT</td>
        <td>{{ $rut }}</td>
        <td class="espaciado-horizontal">EMAIL</td>
        <td>{{ $email }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">TELÉFONO</td>
        <td>{{ $telefono }}</td>
        <td class="espaciado-horizontal">DIRECCIÓN</td>
        <td>{{ $direccion }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">LICENCIA</td>
        <td>{{ $licencia }}</td>
        <td class="espaciado-horizontal">CLASE</td>
        <td>{{ $clase }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">VENCIMIENTO LICENCIA</td>
        <td>{{ \Carbon\Carbon::parse($vence)->format('d-m-Y') }}</td>
        <td colspan="2"></td>
    </tr>
</table>

<!-- Firmas en una sola fila -->
<table style="width: 100%; margin-top: 60px; font-size: 12px; text-align: center; border: none;">
    <tr>
        <td style="width: 50%; border: none;">
            <div style="border-top: 1px solid black; margin-bottom: 4px; width: 80%; margin-left: auto; margin-right: auto;"></div>
            Firma Cliente
        </td>
        <td style="width: 50%; border: none;">
            <div style="border-top: 1px solid black; margin-bottom: 4px; width: 80%; margin-left: auto; margin-right: auto;"></div>
            Firma Empresa
        </td>
    </tr>
</table>


<div class="footer">
    <p style="text-align: center;">
        Declaro conocer y aceptar los términos y condiciones del contrato de arriendo.
    </p>
</div>

<!-- Segunda página con reglas -->
<div class="page-break"></div>

<div style="position: relative; min-height: 100vh; padding-bottom: 150px; font-size: 12px;">

    <div style="font-weight: bold; text-transform: uppercase; font-size: 14px; margin: 15px 0; text-align: center;">
        PARA MEJOR ENTENDIMIENTO, SE DEBERÁ OBSERVAR LAS SIGUIENTES NORMAS
    </div>

    <div style="height: 2px; background-color: black; width: 100%; margin: 10px 0;"></div>

    <div style="max-width: 720px; margin: auto; padding: 0 20px;">
        @foreach($reglas as $i => $regla)
            <div style="margin-bottom: 8px; page-break-inside: avoid; word-wrap: break-word; white-space: normal; line-height: 1.4;">
                <strong>{{ $i + 1 }}.</strong> {{ $regla->description }}
            </div>
        @endforeach
    </div>


    <div style="position: absolute; bottom: 20px; width: 100%; font-size: 12px;">
        <div style="height: 2px; background-color: black; width: 100%; margin: 10px 0;"></div>

        <table style="width: 100%; border: none; text-align: left;">
            <tr>
                <td style="width: 50%; vertical-align: top; border: none;">
                    <img src="{{ $url_logo }}" alt="Logo Empresa" style="max-height: 80px;">
                </td>
                <td style="width: 50%; vertical-align: top; border: none; text-align: right;">
                    <div style="height: 1px; background-color: black; width: 100%; margin: 6px 0;"></div>
                    <div style="margin-top: 6px; font-weight: bold;">DIRECCIÓN DE SUCURSAL</div>
                    <div style="margin-top: 4px;">{{ $direccion_sucursal }}</div>
                    <div style="height: 1px; background-color: black; width: 100%; margin: 6px 0;"></div>
                    <div style="margin-top: 6px; font-weight: bold;">Datos de contacto</div>
                    @foreach($datos_contacto as $contacto)
                    <p style="margin: 4px 0;">{{ $contacto['tipo'] }}: {{ $contacto['dato'] }}</p>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
