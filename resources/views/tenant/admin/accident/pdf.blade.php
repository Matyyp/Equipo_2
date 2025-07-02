<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Siniestro</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
        }
        .logo-top {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-top img {
            height: 120px;
            width: auto;
        }
        h1 {
            font-size: 24px; 
            text-align: center;
            margin-bottom: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-family: Arial, sans-serif;
        }
        td, th {
            border: 1.5px solid #222;
            padding: 8px 6px;
            vertical-align: top;
            font-size: 13px;
        }
        .label {
            font-weight: bold;
            background: #f6f6f6;
            width: 28%;
            white-space: nowrap;
        }
        .accident-image { 
            width: 180px; 
            height: 130px;
            object-fit: cover;
            margin: 0 auto 4px auto;
            border: 1px solid #bbb;
            display: block;
        }
        .photo-title {
            font-size: 12px;
            color: #444;
            margin-bottom: 8px;
        }
        .photos-table-inner {
            width: 100%;
            border: none;
        }
        .photos-table-inner td {
            border: none;
            text-align: center;
            vertical-align: top;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="logo-top">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo Empresa">
        @endif
    </div>

    <h1>Reporte de Siniestro</h1>
    
    <table>
        <tr>
            <td class="label">Nombre Siniestro:</td>
            <td>{{ $accidente->name_accident }}</td>
        </tr>
        <tr>
            <td class="label">Descripción:</td>
            <td>{{ $accidente->description }}</td>
        </tr>
        <tr>
            <td class="label">N° Factura:</td>
            <td>{{ $accidente->bill_number }}</td>
        </tr>
        <tr>
            <td class="label">Descripción término:</td>
            <td>{{ $accidente->description_accident_term }}</td>
        </tr>
        <tr>
            <td class="label">Estado:</td>
            <td>
                {{ $accidente->status === 'in progress' ? 'En progreso' : 'Completado' }}
            </td>
        </tr>
        @if($rentalCar)
        <tr>
            <td class="label">Vehículo:</td>
            <td>
                {{ $rentalCar->plate_number }} 
                | {{ $rentalCar->brand->name_brand ?? '' }} 
                | {{ $rentalCar->model->name_model ?? '' }}
            </td>
        </tr>
        @endif
        <tr>
            <td class="label">Fecha de registro:</td>
            <td>{{ $accidente->created_at }}</td>
        </tr>
        <tr>
            <td class="label">Fotos del siniestro:</td>
            <td>
                @if(isset($photosBase64) && count($photosBase64) > 0)
                    @php
                        $cols = 3; // Fotos por fila
                        $totalPhotos = count($photosBase64);
                    @endphp
                    <table class="photos-table-inner">
                        <tr>
                        @foreach($photosBase64 as $idx => $photo)
                            @if($idx > 0 && $idx % $cols === 0)
                                </tr><tr>
                            @endif
                            <td>
                                <div class="photo-title">Foto {{ $idx+1 }}</div>
                                <img src="{{ $photo }}" class="accident-image" alt="Foto accidente">
                            </td>
                        @endforeach
                        @php $rest = $totalPhotos % $cols; @endphp
                        @if($rest > 0 && $rest < $cols)
                            @for($i = 0; $i < $cols - $rest; $i++)
                                <td></td>
                            @endfor
                        @endif
                        </tr>
                    </table>
                @else
                    <span><i>No hay fotos</i></span>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>