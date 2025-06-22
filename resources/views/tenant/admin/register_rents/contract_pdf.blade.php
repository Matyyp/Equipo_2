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

        .titulo h3 {
            margin: 0;
        }

        .footer {
            margin-top: 60px;
            font-size: 12px;
        }

        .firmas {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .firma-box {
            width: 45%;
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
    </style>
</head>
<body>

<div class="logo-top">
    <img src="{{ $url_logo }}" alt="Logo Empresa">
</div>

<div class="titulo">
    <h3>CONTRATO DE ARRIENDO VEHÍCULO</h3>
</div>

<div class="linea-gruesa"></div>

<table>
    <tr>
        <td class="espaciado-horizontal">MARCA</td>
        <td>{{ $marca }}</td>
        <td class="espaciado-horizontal">MODELO</td>
        <td>{{ $modelo }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">PATENTE</td>
        <td>{{ $patente }}</td>
        <td class="espaciado-horizontal">KM SALIDA</td>
        <td>{{ $km_exit }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">COMBUSTIBLE SALIDA</td>
        <td>{{ $combustible }}</td>
        <td class="espaciado-horizontal">GARANTÍA</td>
        <td>${{ number_format($garantia, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">PAGO TOTAL</td>
        <td>${{ number_format($pago, 0, ',', '.') }}</td>
        <td class="espaciado-horizontal">INICIO</td>
        <td>{{ \Carbon\Carbon::parse($inicio)->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">TÉRMINO</td>
        <td>{{ \Carbon\Carbon::parse($termino)->format('d-m-Y') }}</td>
        <td class="espaciado-horizontal">OBSERVACIONES</td>
        <td>{{ $observacion }}</td>
    </tr>
</table>

<div class="linea-gruesa"></div>

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

<div class="firmas">
    <div class="firma-box">
        <div class="line"></div>
        <div>Firma Cliente</div>
    </div>
    <div class="firma-box">
        <div class="line"></div>
        <div>Firma Empresa</div>
    </div>
</div>

<div class="footer">
    <p style="text-align: center;">
        Declaro conocer y aceptar los términos y condiciones del contrato de arriendo.
    </p>
</div>

</body>
</html>
