<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enlace expirado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        .box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #dc3545;
        }

        a {
            margin-top: 1.5rem;
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <div class="box">
        <h1>⚠️ Enlace expirado</h1>
        <p>Este enlace ya no es válido o ha caducado.</p>
        <p>Por favor, comuníquese con la empresa para recibir un nuevo contrato.</p>
        <a href="https://{{ request()->getHost() }}">Ir al sitio</a>
    </div>
</body>
</html>
