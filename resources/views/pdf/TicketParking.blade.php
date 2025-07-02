<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ticket de Arriendo</title>
    <style>
        @page {
            size: 300pt 125pt;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 300pt;
            height: 125pt;
            font-family: 'Courier New', monospace;
            font-size: 7pt;
            box-sizing: border-box;
        }
        header, footer {
            width: 100%;
            text-align: center;
            font-weight: bold;
            font-size: 6pt;
            padding: 0;
        }
        header {
            border-bottom: 1px dashed #000;
            margin-top: 11pt;
            padding-bottom: 6pt; /* espacio hacia abajo */
        }
        .content {
            padding-left: 10pt;
            padding-right: 10pt;
            margin-top: 6pt;
        }
        footer {
            border-top: 1px dashed #000;
            margin-top: 6pt;
            padding-top: 6pt; /* espacio hacia arriba */
        }
        .line {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 0.5pt;
            margin-top: 3pt;
        }
        .label {
            font-weight: bold;
            margin-right: 2pt;
            display: inline;
        }
        .value {
            display: inline;
        }
    </style>
</head>
<body>
    <header>TICKET DE ESTACIONAMIENTO</header>

    <div class="content">
        <div class="line"><span class="label">Cliente:</span><span class="value">{{ $nombre }}</span></div>
        <div class="line"><span class="label">Teléfono:</span><span class="value">{{ $telefono }}</span></div>
        <div class="line"><span class="label">Vehículo:</span><span class="value">{{ $marca }} {{ $modelo }}</span></div>
        <div class="line"><span class="label">Patente:</span><span class="value">{{ $patente }}</span></div>
        <div class="line"><span class="label">Periodo:</span><span class="value">{{ $inicio }} a {{ $termino }}</span></div>
        <div class="line"><span class="label">Lavado:</span><span class="value">{{ $lavado ? 'Sí' : 'No' }}</span></div>
    </div>

    <footer>
        {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>
</html>
