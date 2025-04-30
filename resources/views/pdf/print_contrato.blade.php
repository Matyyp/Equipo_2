<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Imprimiendo Contrato...</title>
  <style>
    html, body { margin:0; height:100%; }
    object { width:100%; height:100%; border:none; }
  </style>
</head>
<body>
  {{-- Incrusta el PDF en base64; el usuario no lo ve porque lanzamos print() inmediatamente --}}
  <object 
    id="pdfObject"
    data="data:application/pdf;base64,{{ $pdfBase64 }}" 
    type="application/pdf">

    Tu navegador no soporta incrustar PDFs. 
    <a href="data:application/pdf;base64,{{ $pdfBase64 }}">Descargar PDF</a>
  </object>

  <script>
    window.onload = function() {
      setTimeout(() => window.print(), 300);
    };
    window.onafterprint = () => window.close();
  </script>
</body>
</html>
