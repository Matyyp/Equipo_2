<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tickets Verticales Full</title>
<style>
        @page {
            size:letter portrait; /* A4 Vertical */
            margin: 0;         /* IMPORTANTE: Margen 0 para controlar todo nosotros */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Courier New", monospace;
            font-size: 11pt;
        }

        .hoja {
            width: 100%;
            position: relative;
        }

        .ticket-columna {
            float: left;
            /* Ajustamos el ancho para que entren exactamente 10 u 11 en A4 (21cm) */
            width: 1.9cm;  
            height: 27.9cm; /* Altura total de la hoja */
            overflow: hidden;
            position: relative; 
            padding: 0;
            margin: 0/* Necesario para que lo de adentro se posicione bien */
        }

        .contenido-rotado {
            /* Dimensiones invertidas: El ancho aquí será la altura visual del ticket */
            width: 26cm;  
            height: 1.9cm;
            /* POSICIONAMIENTO MAGISTRAL */
            position: absolute;
            top: 0;      /* Anclamos arriba */
            left: 0;     /* Anclamos a la izquierda */

            /* TRUCO: Rotamos desde la esquina superior izquierda.
               Al rotar -90deg, el ticket se "sale" hacia arriba y a la izquierda.
               Usamos translateX y translateY para volver a meterlo en la columna.
            */
            transform-origin: top left;
            transform: rotate(-90deg) translateX(-29cm); 
            /* translateX negativo empuja el ticket hacia abajo visualmente */
            
            display: block;
            padding-left: 0; /* Margen "arriba" del ticket (que ahora es izquierda) */
 /* Centrado vertical del texto */
            white-space: nowrap;
        }

        .texto-ticket {
            display: inline-block;
            vertical-align: middle;
            
        }

        .patente-box { 
            padding: 0px 0px; 
            font-weight: bold; 
            margin-left: 7.2cm;
            line-height: 1.3cm;
        }
        .patente-box-dos { 
            padding: 0; 
            font-weight: bold;
            margin: 0; 
            margin-left: 7.2cm;
            margin-top: -0.4cm;
        }

        .salto-pagina {
            clear: both;
            page-break-after: always;
            height: 0;
            display: block;
        }
    </style>
</head>
<body>

<div class="hoja">
    @foreach($tickets as $t)
        <div class="ticket-columna">
            <div class="contenido-rotado">
                <div class="seccion texto-centro">
                    <span class="patente-box">{{ $t['telefono'] }} - {{$t['nombre'] }} - {{ $t['patente'] }} - {{ $t['modelo'] }} </span>
                    <span class="patente-box-dos">{{ $t['inicio'] }} al {{ $t['termino'] }}  / Lavado: {{ $t['lavado'] ? 'SÍ' : 'NO' }}</span>
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