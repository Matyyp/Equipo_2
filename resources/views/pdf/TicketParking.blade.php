<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket Centrado</title>
    <style>
        @page {
            size: 300pt 125pt; /* Horizontal: 10.5cm x 4.4cm */
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 300pt;
            height: 125pt;
            font-family: Arial, sans-serif;
            font-size: 9.5px;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;   /* ← CENTRO HORIZONTAL */
            align-items: center;       /* ← CENTRO VERTICAL */
            text-align: center;
        }

        table {
            border-collapse: collapse;
        }

        td {
            padding: 2px 6px;
            vertical-align: top;
            white-space: nowrap;
        }

        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <table>
        <tr>
            <td class="label">Nombre:</td>
            <td>{{ $nombre }}</td>
            <td class="label">Marca:</td>
            <td>{{ $marca }} {{ $modelo }}</td>
        </tr>
        <tr>
            <td class="label">Teléfono:</td>
            <td>{{ $telefono }}</td>
            <td class="label">Patente:</td>
            <td>{{ $patente }}</td>
        </tr>
        <tr>
            <td class="label">Fechas:</td>
            <td colspan="3">{{ $inicio }} al {{ $termino }}</td>
        </tr>
    </table>
</div>

</body>
</html>
