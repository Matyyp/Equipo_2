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
            margin-top: 0px;
            margin-bottom: 0px;
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

.footer-pagina1 {
    width: 100%;
    font-size: 12px;
    text-align: center;
    margin-top: 5px;
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
            position: relative;
        }

    }
    </style>
</head>
<body>

<!-- Dentro de logo-top -->
<div class="logo-top">
    <table style="width: 100%; border: none; text-align: left;">
        <tr>
            <!-- Columna izquierda: logo -->
            <td style="width: 60%; vertical-align: top; border: none;">
                <img src="{{ $url_logo }}" alt="Logo Empresa" style="max-height: 200px;">
            </td>

            <!-- Columna derecha: datos contacto -->
            <td style="width: 40%; vertical-align: top; border: none; text-align: right; font-size: 15px;">
                <div style="margin-top: 6px; font-weight: bold;">RENÉ SANDOVAL PÉREZ</div>
                <div style="margin-top: 4px;">RUT: 8.232.253-1</div>
                <div style="height: 1px; background-color: black; width: 100%; margin: 6px 0;"></div>
                <div style="margin-top: 6px; font-weight: bold;">DIRECCIÓN:</div>
                <div style="margin-top: 4px;">Calle Mackena N°768, Balmaceda</div>
                <div style="margin-top: 4px;">Comuna de Coyhaique</div>
                <div style="height: 1px; background-color: black; width: 100%; margin: 6px 0;"></div>
                <div style="margin-top: 4px;">+56 9 95110639 +56 9 42644477 +56 9 79196825</div>
                <div style="margin-top: 4px;">rentacarencoyhaique@gmail.com</div>
                <div style="margin-top: 4px;">www.rentacarencoyhaique2.cl</div>
            </td>
        </tr>
    </table>
</div>


<div class="titulo">
    <h3>COMPROBANTE DE CUSTODIA PRIVADA</h3>
    <h3>DE VEHÍCULO PARTICULAR</h3>
</div>

<div class="linea-gruesa"></div>

<div class="subtitulo">DATOS DEL VEHÍCULO</div>

<table>
    <tr>
        <td class="espaciado-horizontal" style="width: 25%;">MARCA:</td>
        <td style="width: 25%;"></td>
        <td class="espaciado-horizontal" style="width: 25%;">INGRESO CUSTODIA:</td>
        <td style="width: 25%;"></td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">MODELO:</td>
        <td></td>
        <td class="espaciado-horizontal">TÉRMINO CUSTODIA:</td>
        <td></td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">PATENTE:</td>
        <td></td>
        <td class="espaciado-horizontal">DÍAS:</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2" style="padding: 8px; vertical-align: top;">OBSERVACIÓN:</td>
        <td class="espaciado-horizontal">EXTRA:</td>
        <td></td>
    </tr>
    <tr>
        <td class="espaciado-horizontal">VALOR TOTAL:</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 8px;">
            CUSTODIA EN:<br>
            Calle Mackena N°768, Balmaceda: <br>
            Comuna de Coyhaique.
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
        <td colspan="1">NOMBRE DEL PROPIETARIO: </td>
        <td colspan="3"> </td>
    </tr>
    <tr>
        <td colspan="1">TELÉFONO CONTACTO:</td>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="4" style="font-weight: bold; font-size: 12px; padding: 8px; background-color: #f5f5f5;">
            EL VALOR DE ESTACIONAMIENTO DIARIO, SE DEBE CONSIDERAR DESDE EL DÍA QUE INGRESA EL VEHÍCULO, HASTA EL DÍA QUE SE RETIRA MISMO VEHÍCULO, INDEPENDIENTE DE CUANTAS HORAS HAYA ESTADO SU VEHÍCULO EN EL RECINTO
        </td>
    </tr>
    <tr>
        <td colspan="2">VALOR DIARIO</td>
        <td colspan="2"> <span style="font-size: 12px;">(Iva incluido) $3.000</span> </td>
    </tr>
    <tr>
        <td colspan="2">LAVADO</td>
        <td colspan="2"> <span style="font-size:12px;">(Iva incluido)</span></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center; padding: 14px;">
            <span style="color: red; font-weight: bold; font-size: 22px; text-transform: uppercase;">
                Horario de atención : {{ $horario }} hrs.
            </span>
        </td>
    </tr>
</table>

<div style="text-align: center; font-weight: bold; font-size: 12px; margin: 0 0 8px 0;">
    EL PAGO POR CUSTODIA PRIVADO, SE PUEDE REALIZAR MEDIANTE TARJETA CRÉDITO (SIN CUOTAS),<br>
    DÉBITO, EFECTIVO Y/O TRANSFERENCIAS, SOLO SE EXTENDERÁN BOLETAS DE VENTA.
</div>

<div class="footer-pagina1">
    <div style="height: 1px; background-color: black; width: 100%; margin: 8px 0;"></div>

    <table style="width: 100%; font-size: 10px; margin-bottom: 0; text-align: justify;">
        <tr>
            <td style="width: 50%; border: none; padding-right: 10px; padding-top: 4px; text-transform: uppercase;">
                Declaro conocer y acepto en todas sus partes las estipulaciones del presente contrato contenidas en este documento.
            </td>
            <td style="width: 50%; border: none; padding-left: 10px; padding-top: 4px; text-transform: uppercase;">
                Los daños que tenga un vehículo al ingreso en custodia privada no son responsabilidad del arrendador, al igual si existiese ruedas con neumáticos pinchados u otro daño.
            </td>
        </tr>
    </table>

    <div style="height: 1px; background-color: black; width: 100%; margin: 8px 0;"></div>

    <table style="width: 100%; font-size: 12px; text-align: center; border: none; margin-top: 40px;">
        <tr>
            <td style="width: 45%; border: none; padding-top: 6px; font-weight: bold;">
                s
            </td>
            <td style="width: 10%; border: none;"></td>
            <td style="width: 45%; border: none; border-top: 1px solid black; padding-top: 6px; font-weight: bold;">
                NOMBRE, FIRMA Y RUT DEL ARRENDATARIO
            </td>
        </tr>
    </table>
</div>

</body>
</html>

