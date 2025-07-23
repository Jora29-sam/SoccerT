<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
        }

        h1 {
            margin-bottom: 30px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 14px 28px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .volver {
            margin-top: 40px;
        }
        
        .volver a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }
        
        .volver a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Catálogo de camisetas disponible</h1>

    <a href="generar_pdf.php" class="btn" aria-label="Descargar catálogo en PDF de camisetas de fútbol">Descargar Catálogo en PDF</a>

    <div class="volver">
        <a href="index.php">← Volver al inicio</a>
    </div>

</body>
</html>
