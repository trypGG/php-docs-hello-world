<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Registro de Usuario</h2>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = trim($_POST["nombre"]);
        $correo = trim($_POST["correo"]);

        if (!empty($nombre) && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $correo);
            $stmt->execute();
            echo '<div class="alert alert-success">Registro exitoso.</div>';
            $stmt->close();
        } else {
            echo '<div class="alert alert-danger">Por favor ingresa datos válidos.</div>';
        }
    }
    ?>

    <form method="POST" action="" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="consultar.php" class="btn btn-secondary">Ver registros</a>
    </form>
</div>
</body>
</html>

