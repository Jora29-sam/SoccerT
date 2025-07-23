<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Formulario de Usuario</title>
    <style>
        body {
            margin: 0;
            padding: 20px 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            min-height: 100vh;
            display: flex;
            justify-content: center; /* centrado horizontal */
            align-items: flex-start; /* evita cortar vertical, permite scroll */
        }

        .formulario-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="formulario-container">
    <h2>Datos del Usuario</h2>
    <form action="guardar_usuario.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="submit" value="Guardar">
    </form>
</div>

</body>
</html>
