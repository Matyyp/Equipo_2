<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Custodia</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
    }

    @media screen {
        body {
            margin: 10px;
            margin-top:0px;
            margin-bottom:0px;
        }
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
        font-weight: normal;
        font-family: Arial, sans-serif;
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

    .titulo h3:first-child {
        font-size: 28px;
        margin: 0;
    }

    .titulo h3:last-child {
        font-size: 20px;
        margin: 0;
    }

    .subtitulo {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .firmas {
        margin-top: 60px;
        clear: both;
    }

    .firma-box {
        text-align: center;
        width: 50%;
        float: left;
    }

    .logo-top {
        text-align: center;
        margin-bottom: 20px;
    }

    @media print {
        @page {
            margin: 0;
        }

        body {
            width: 100%;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
        }

        .logo-top {
            position: absolute;
            top: 0 !important;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 1000;
            margin: 0 !important;
            padding: 0 !important;
        }

        .logo-top img {
            max-height: 50px;
            height: auto;
            width: auto;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        .titulo,
        .linea-gruesa,
        .subtitulo,
        table,
        .firmas {
            margin-top: 100px;
        }
    }
    </style>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>

<div class="logo-top">
    <img src="file://{{ $url_logo }}" alt="Logo Empresa">
</div>

<div class="titulo">
    <h3>CONTRATO DE CUSTODIA PRIVADA</h3>
    <h3>DE VEHÍCULO PARTICULAR</h3>
</div>

<div class="linea-gruesa"></div>

<div class="subtitulo">DATOS DEL VEHÍCULO</div>

<table>
    <tr>
        <td class="espaciado-horizontal" style="width: 25%;">MARCA:</td>
        <td style="width: 25%;">{{ $marca }}</td>
        <td class="espaciado-horizontal" style="width: 25%;">INICIO CONTRATO:</td>
        <td style="width: 25%;">{{ $inicio }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">MODELO:</td>
        <td>{{ $modelo }}</td>
        <td class="espaciado-horizontal">TÉRMINO CONTRATO:</td>
        <td>{{ $termino }}</td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">PATENTE:</td>
        <td>{{ $patente }}</td>
        <td colspan="2" rowspan="3"></td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">KM. AL LLEGAR:</td>
        <td></td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">KM. SALIDA:</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 8px;">
            CUSTODIA EN:<br>
            {{ $direccion_sucursal }}
        </td>
        <td colspan="2" style="text-align: center; padding: 8px;">
            <div style="font-weight: bold; font-size: 16px;">IMPORTANTE</div>
            <div style="font-size: 13px;">
                TODOS LOS VALORES INDICADOS SE<br>
                ENCUENTRAN CON EL <span style="font-weight: bold;">19% IVA INCLUIDO</span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">NOMBRE DEL PROPIETARIO: {{ $nombre }} </td>
    </tr>
    <tr>
        <td colspan="4">DIRECCIÓN: </td>
    </tr>
    <tr>
        <td>RUT N°</td>
        <td></td>
        <td>VALOR TOTAL:</td>
        <td>{{ '$' . number_format($valor_total, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td colspan="4">TELÉFONO CONTACTO: {{ $telefono }}</td>
    </tr>
    <tr>
        <td colspan="2">VALOR DIARIO</td>
        <td colspan="2">{{ '$' . number_format($valor_estacionamiento, 0, ',', '.') }} <span style="color: gray;">(Iva incluido)</span></td>
    </tr>
    <tr>
        <td colspan="2">LAVADO AUTO</td>
        <td colspan="2">{{ '$' . number_format(20000, 0, ',', '.') }} <span style="color: gray;">(Iva incluido)</span></td>
    </tr>
    <tr>
        <td colspan="2">LAVADO STATION MEDIANO</td>
        <td colspan="2">{{ '$' . number_format(25000, 0, ',', '.') }} <span style="color: gray;">(Iva incluido)</span></td>
    </tr>
    <tr>
        <td colspan="2">LAVADO CAMIONETAS, VEHÍCULOS GRANDES Y/O LIMPIEZA MAYOR</td>
        <td colspan="2">{{ '$' . number_format(30000, 0, ',', '.') }} <span style="color: gray;">(Iva incluido)</span></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; padding: 14px;">
            <span style="color: red; font-weight: bold; font-size: 22px; text-transform: uppercase;">
                Horario de atención : {{ $horario }} hrs.
            </span>
        </td>
    </tr>
</table>

<div style="text-align: center; font-weight: bold; font-size: 12px; margin-top: 0px; margin-bottom: 8px;">
    EL PAGO POR CUSTODIA PRIVADO, SE PUEDE REALIZAR MEDIANTE TARJETA CRÉDITO (SIN CUOTAS),<br>
    DÉBITO, EFECTIVO Y/O TRANSFERENCIAS, SOLO SE EXTENDERÁN BOLETAS DE VENTA.
</div>

<div style="height: 1px; background-color: black; width: 100%; margin: 8px 0;"></div>

<table style="width: 100%; font-size: 10px; margin-bottom: 0; text-align: justify;">
    <tr>
        <td style="width: 20%; border: none; padding-right: 10px; padding-top: 4px; text-transform: uppercase;">
            Declaro conocer y acepto en todas sus partes las estipulaciones del presente contrato contenidas en este documento.
        </td>
        <td style="width: 20%; border: none; padding-left: 10px; padding-top: 4px; text-transform: uppercase;">
            Los daños que tenga un vehículo al ingreso en custodia privada no son responsabilidad del arrendador, al igual si existiese ruedas con neumáticos pinchados u otro daño.
        </td>
    </tr>
</table>

<div style="height: 1px; background-color: black; width: 100%; margin: 8px 0;"></div>

<table style="width: 100%; font-size: 12px; text-align: center; border: none; margin-top: 50px;">
    <tr>
        <td style="width: 45%; border: none; border-top: 1px solid black; padding-top: 6px; font-weight: bold;">
            NOMBRE, FIRMA Y RUT DEL ARRENDADO
        </td>
        <td style="width: 10%; border: none;"></td>
        <td style="width: 45%; border: none; border-top: 1px solid black; padding-top: 6px; font-weight: bold;">
            NOMBRE, FIRMA Y RUT DEL ARRENDATARIO
        </td>
    </tr>
</table>

<div class="page-break"></div>

<div style="position: relative; min-height: 1000px;">

    <div style="border: 1px solid black; padding: 10px; font-size: 12px; text-align: justify; text-transform: uppercase;">
        <span style="color: red; font-weight: bold;">NOTA IMPORTANTE:</span>
        “Toda garantía por daños que pueda producirse mientras el vehículo indicado precedentemente, esté en el recinto Calle {{ $direccion_sucursal }}, se perderá en forma inmediata, desde el momento en que el dueño o titular del vehículo en custodia privada, facilite dicho vehículo a un tercero, siendo este familiar, amigo u otra condición”
    </div>

    <div style="font-weight: bold; text-transform: uppercase; font-size: 14px; margin-top: 10px; text-align: center;">
        PARA MEJOR ENTENDIMIENTO, SE DEBERÁ OBSERVAR LAS SIGUIENTES NORMAS
    </div>

    <div style="height: 2px; background-color: black; width: 100%; margin: 4px 0;"></div>

    {{-- REGLAS CON ESTILO APLICADO --}}
    <div style="font-size: 13px; margin-top: 10px; text-transform: uppercase; font-weight: bold;">
        @foreach($reglas as $i => $regla)
            <p>{{ $i + 1 }}. {{ $regla->description }}</p>
        @endforeach
    </div>

    <div style="position: absolute; bottom: 20px; width: 100%; font-size: 12px;">
        <div style="height: 2px; background-color: black; width: 100%; margin: 10px 0;"></div>

        <table style="width: 100%; border: none; text-align: left;">
            <tr>
                <td style="width: 50%; vertical-align: top; border: none;">
                    <img src="file://{{ $url_logo }}" alt="Logo Empresa" style="max-height: 200px;">
                </td>
                <td style="width: 0%; vertical-align: top; border: none; text-align: right;">
                    <div style="font-weight: bold; font-size: 14px;">{{ $dueño }}</div>
                    <div style="margin-top: 2px;">RUT: {{ $rut }}</div>
                    <div style="height: 1px; background-color: black; width: 100%; margin: 6px 0;"></div>
                    <div style="margin-top: 6px; font-weight: bold;">DIRECCIÓN DE SUCURSAL</div>
                    <div style="margin-top: 4px;">{{ $direccion_sucursal }}</div>
                    <div style="height: 1px; background-color: black; width: 100%; margin: 6px 0;"></div>
                    <div style="margin-top: 6px; font-weight: bold;">Datos de contacto</div>
                    @foreach($datos_contacto as $contacto)
                        <p>{{ $contacto->type_contact }}: {{ $contacto->data_contact }}</p>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
