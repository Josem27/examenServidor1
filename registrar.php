<?php
session_start();

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_expediente = $_POST["numero_expediente"];
    $nif = $_POST["nif"];
    $nombre = $_POST["nombre"];
    $apellido1 = $_POST["apellido1"];
    $apellido2 = $_POST["apellido2"];
    $email = $_POST["email"];
    $telefono_movil = $_POST["telefono_movil"];

    // Obtener la imagen
    $imagen = $_FILES["imagen"]["name"];
    $imagen_temporal = $_FILES["imagen"]["tmp_name"];
    $ruta = "images/" . $imagen;

    // Mover la imagen a la carpeta de uploads
    move_uploaded_file($imagen_temporal, $ruta);

    // Realizar el registro del estudiante
    try {
        $stmt = $dbh->prepare("INSERT INTO estudiante (numero_expediente, nif, nombre, apellido1, apellido2, email, telefono_movil, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numero_expediente, $nif, $nombre, $apellido1, $apellido2, $email, $telefono_movil, $ruta]);

        header("Location: index.php");
        exit();
    } catch (PDOException $ex) {
        $error = "Error al registrar el estudiante: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Estudiante</title>
    <!-- Agregar enlaces a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Registro de Estudiante</h2>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="numero_expediente">Número de Expediente:</label>
                        <input type="text" id="numero_expediente" name="numero_expediente" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nif">NIF:</label>
                        <input type="text" id="nif" name="nif" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido1">Apellido 1:</label>
                        <input type="text" id="apellido1" name="apellido1" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido2">Apellido 2:</label>
                        <input type="text" id="apellido2" name="apellido2" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono_movil">Teléfono Móvil:</label>
                        <input type="text" id="telefono_movil" name="telefono_movil" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen:</label>
                        <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Registrar Estudiante</button>
                </form>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <!-- Botón de vuelta a listado_estudiantes.php -->
                <a href="index.php" class="btn btn-secondary btn-block mt-3">Listado de Estudiantes</a>
            </div>
        </div>
    </div>

    <!-- Agregar enlaces a Bootstrap JS y jQuery al final del cuerpo -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
