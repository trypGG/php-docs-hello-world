<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario con Azure SQL</title>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --error-color: #f72585;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
            transition: all 0.3s ease;
        }
        
        .form-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-weight: 500;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-submit:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .response {
            margin-top: 30px;
            padding: 20px;
            border-radius: 6px;
            background-color: #e8f4fd;
            border-left: 4px solid var(--success-color);
        }
        
        .error {
            border-left-color: var(--error-color) !important;
            background-color: #fef0f5 !important;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Registro de Usuario</h1>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="nombre">Nombre(s)</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="primer_apellido">Primer Apellido</label>
                <input type="text" id="primer_apellido" name="primer_apellido" required>
            </div>
            
            <div class="form-group">
                <label for="segundo_apellido">Segundo Apellido</label>
                <input type="text" id="segundo_apellido" name="segundo_apellido">
            </div>
            
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
            
            <button type="submit" name="enviar" class="btn-submit">Enviar Datos</button>
        </form>
        
        <?php
        // Configuración para Azure SQL (usa variables de entorno en producción)
        $serverName = "dbserver21051392.database.windows.net";
        $database = "BasedeDatos_2025-05-24T05-51Z";
        $username = "U21051392@dbserver21051392";
        $password = "brayanQ12345";

        try {
            $conn = new PDO(
                "sqlsrv:Server=$serverName,1433;Database=$database", 
                $username, 
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            // Crear tabla si no existe (sintaxis para SQL Server)
            $sql = "IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'usuarios')
                    CREATE TABLE usuarios (
                        id INT IDENTITY(1,1) PRIMARY KEY,
                        nombre NVARCHAR(50) NOT NULL,
                        primer_apellido NVARCHAR(50) NOT NULL,
                        segundo_apellido NVARCHAR(50),
                        correo NVARCHAR(100) NOT NULL,
                        telefono NVARCHAR(20) NOT NULL,
                        fecha_registro DATETIME DEFAULT GETDATE()
                    )";
            $conn->exec($sql);
            
        } catch(PDOException $e) {
            echo '<div class="response error">';
            echo '<h3>Error de conexión:</h3>';
            echo '<p>'.htmlspecialchars($e->getMessage()).'</p>';
            echo '</div>';
        }

        if (isset($_POST['enviar'])) {
            try {
                // Insertar datos en la base de datos
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, primer_apellido, segundo_apellido, correo, telefono) 
                                       VALUES (:nombre, :primer_apellido, :segundo_apellido, :correo, :telefono)");
                
                $stmt->bindParam(':nombre', $_POST['nombre']);
                $stmt->bindParam(':primer_apellido', $_POST['primer_apellido']);
                $stmt->bindParam(':segundo_apellido', $_POST['segundo_apellido']);
                $stmt->bindParam(':correo', $_POST['correo']);
                $stmt->bindParam(':telefono', $_POST['telefono']);
                
                $stmt->execute();
                
                // Mostrar mensaje de éxito
                echo '<div class="response">';
                echo '<h3>Datos Guardados Correctamente:</h3>';
                echo '<p><strong>Nombre:</strong> '.htmlspecialchars($_POST['nombre']).'</p>';
                echo '<p><strong>Primer Apellido:</strong> '.htmlspecialchars($_POST['primer_apellido']).'</p>';
                echo '<p><strong>Segundo Apellido:</strong> '.htmlspecialchars($_POST['segundo_apellido']).'</p>';
                echo '<p><strong>Correo:</strong> '.htmlspecialchars($_POST['correo']).'</p>';
                echo '<p><strong>Teléfono:</strong> '.htmlspecialchars($_POST['telefono']).'</p>';
                echo '</div>';
                
            } catch(PDOException $e) {
                echo '<div class="response error">';
                echo '<h3>Error al guardar los datos:</h3>';
                echo '<p>'.htmlspecialchars($e->getMessage()).'</p>';
                echo '</div>';
            }
        }

        // Mostrar registros existentes
        try {
            $stmt = $conn->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
            $usuarios = $stmt->fetchAll();
            
            if (!empty($usuarios)) {
                echo '<h3 style="margin-top: 30px;">Usuarios Registrados</h3>';
                echo '<table>';
                echo '<tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Correo</th><th>Teléfono</th><th>Fecha Registro</th></tr>';
                
                foreach ($usuarios as $usuario) {
                    echo '<tr>';
                    echo '<td>'.$usuario['id'].'</td>';
                    echo '<td>'.htmlspecialchars($usuario['nombre']).'</td>';
                    echo '<td>'.htmlspecialchars($usuario['primer_apellido'].' '.$usuario['segundo_apellido']).'</td>';
                    echo '<td>'.htmlspecialchars($usuario['correo']).'</td>';
                    echo '<td>'.htmlspecialchars($usuario['telefono']).'</td>';
                    echo '<td>'.$usuario['fecha_registro'].'</td>';
                    echo '</tr>';
                }
                
                echo '</table>';
            }
            
        } catch(PDOException $e) {
            echo '<div class="response error">';
            echo '<h3>Error al consultar registros:</h3>';
            echo '<p>'.htmlspecialchars($e->getMessage()).'</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
