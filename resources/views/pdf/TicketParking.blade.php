<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tickets Verticales Full</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0; 
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Courier New", monospace;
            font-size: 12pt; 
        }

        .hoja {
            width: 100%;
            height: 100%;
        }

        .ticket-columna {
            float: left;
            width: 1.9cm;  
            height: 24cm;  
            box-sizing: border-box; 
            position: relative; 
            page-break-inside: avoid;
        }

        .contenido-rotado {
            width: 24cm;  
            height: 1.9cm; 
            position: absolute;
            bottom: 0;
            left: 10px; 
            transform-origin: bottom left; 
            transform: rotate(-90deg);
            display: flex;
            align-items: center; 
            justify-content: space-between; 
            padding: 0 4mm; 
            box-sizing: border-box;
        }

        .seccion { white-space: nowrap; }
        .texto-centro { text-align: center; width: 100%; }
        .patente-box { border: 1px solid #000; padding: 1px 3px; font-weight: bold; }

        .salto-pagina {
            clear: both;          
            page-break-after: always; 
            height: 0;
            margin: 0;
            display: block;
            visibility: hidden;
        }

    </style>
</head>
<body>

<div class="hoja">
    @foreach($tickets as $t)
        <div class="ticket-columna">
            <div class="contenido-rotado">
                <div class="seccion texto-centro">
                    <span class="patente-box"></span>{{ $t['telefono'] }} - {{$t['nombre'] }} - {{ $t['patente'] }} - {{ Str::limit($t['modelo'], 10) }}</span><br>

                    <span style="font-size: 10pt;"> {{ $t['inicio'] }} al {{ $t['termino'] }}  / Lavado: <b>{{ $t['lavado'] ? 'SÍ' : 'NO' }}</span>
                </div>
            </div>
        </div>

        {{-- LÓGICA DE SALTO: --}}
        {{-- Si el contador es múltiplo de 10 (10, 20, 30...) Y no es el último ticket --}}
        @if($loop->iteration % 10 == 0 && !$loop->last)
            <div class="salto-pagina"></div>
        @endif

    @endforeach
</div>

</body>
</html>