{{-- resources/views/pdf/contrato_estacionamiento.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    @page { margin: 15mm; }
    body { font-family: sans-serif; font-size: 12px; line-height: 1.3; }
    .line { display: inline-block; border-bottom: 1px solid #000; min-width: 40mm; padding-left: 2mm; }
    .header { text-align: center; margin-bottom: 5mm; }
    .header h2 { margin: 0; }
    .subheader { font-style: italic; margin-top: 1mm; }
    .section { margin-bottom: 4mm; }
    .firma { position: absolute; bottom: 20mm; width: 100%; text-align: center; }
    .page-break { page-break-after: always; }
    .tarifas { width: 100%; border-collapse: collapse; margin-bottom: 4mm; }
    .tarifas th, .tarifas td { border: 1px solid #000; padding: 4px 6px; text-align: center; }
    .tarifas th { background: #f0f0f0; }
    .texto-reglas { font-size: 10px; text-align: justify; margin-bottom: 4mm; }
  </style>
</head>
<body>

  {{-- =========================
       HOJA 1 – FORMULARIO
       ========================= --}}
  <div class="header">
    <h2>CONTRATO DE ESTACIONAMIENTO</h2>
    <p class="subheader">Original – Cliente</p>
  </div>

  <div class="section">
    <strong>Cliente:</strong>
    <span class="line">{{ $cliente->nombre ?? '__________' }}</span><br>
    <strong>RUT / Cédula:</strong>
    <span class="line">{{ $cliente->rut ?? '__________' }}</span>
  </div>

  <div class="section">
    <strong>Patente:</strong>
    <span class="line">{{ $parking->patente ?? '__________' }}</span><br>
    <strong>Marca:</strong>
    <span class="line">{{ $parking->marca ?? '__________' }}</span>
    &nbsp;
    <strong>Modelo:</strong>
    <span class="line">{{ $parking->modelo ?? '__________' }}</span>
  </div>

  <div class="section">
    <strong>Fecha Ingreso:</strong>
    <span class="line">{{ optional($parking->fecha_ingreso)->format('d/m/Y H:i') ?? '__________' }}</span><br>
    <strong>Fecha Salida:</strong>
    <span class="line">{{ optional($parking->fecha_salida)->format('d/m/Y H:i') ?? '__________' }}</span>
  </div>

  <div class="section">
    <strong>Tarifa Diario:</strong>
    <span class="line">${{ number_format($tarifa_diaria ?? 0,0,',','.') }} (IVA incluido)</span><br>
    <strong>Tarifa Extra:</strong>
    <span class="line">${{ number_format($tarifa_extra ?? 0,0,',','.') }} (IVA incluido)</span>
  </div>

  <div class="section">
    <strong>Días:</strong>
    <span class="line">{{ $dias_totales ?? '0' }}</span><br>
    <strong>Valor Total:</strong>
    <span class="line">${{ number_format($valor_total ?? 0,0,',','.') }}</span>
  </div>

  <div class="section" style="font-size:10px; text-align:center;">
    FUERA DE ESTOS HORARIOS, EL RECINTO ESTARÁ CERRADO.
  </div>

  <div class="firma">
    <span>______________________________________</span><br>
    <span>Firma Cliente</span>
  </div>

  {{-- forzar nueva página --}}
  <div class="page-break"></div>

  {{-- =========================
       HOJA 2 – REGLAS Y DATOS
       ========================= --}}
  <div class="header">
    <h2>CONDICIONES Y TARIFAS</h2>
    <p class="subheader">Copia – Empresa</p>
  </div>

  {{-- Tabla de tarifas --}}
  <table class="tarifas">
    <thead>
      <tr>
        <th>Tipo</th>
        <th>Valor (IVA incluido)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Tarifa Diaria</td>
        <td>${{ number_format($tarifa_diaria ?? 0,0,',','.') }}</td>
      </tr>
      <tr>
        <td>Tarifa Extra</td>
        <td>${{ number_format($tarifa_extra ?? 0,0,',','.') }}</td>
      </tr>
      <tr>
        <td>Tarifa Semanal</td>
        <td>${{ number_format($tarifa_semanal ?? 25000,0,',','.') }}</td>
      </tr>
      <tr>
        <td>Tarifa Mensual</td>
        <td>${{ number_format($tarifa_mensual ?? 30000,0,',','.') }}</td>
      </tr>
    </tbody>
  </table>

  {{-- Texto de reglas --}}
  <div class="texto-reglas">
    El valor del estacionamiento diario se debe considerar desde el día que ingresa el vehículo hasta el día que se retira el mismo vehículo, independiente de cuántas horas haya estado su vehículo en el recinto.
  </div>

  <div class="section">
    <strong>Días:</strong>
    <span class="line">{{ $dias_totales ?? '0' }}</span><br>
    <strong>Valor Total:</strong>
    <span class="line">${{ number_format($valor_total ?? 0,0,',','.') }}</span>
  </div>

  {{-- Firma empresa --}}
  <div class="firma">
    <span>______________________________________</span><br>
    <span>Empresa</span>
  </div>

</body>
</html>
