<?php
session_start();

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la actualización del estudiante en la base de datos
    $idEstudiante = $_POST['idEstudiante'];
    $nuevoNumeroExpediente = $_POST['nuevoNumeroExpediente'];
    $nuevoNif = $_POST['nuevoNif'];
    $nuevoNombre = $_POST['nuevoNombre'];
    $nuevoApellido1 = $_POST['nuevoApellido1'];
    $nuevoApellido2 = $_POST['nuevoApellido2'];
    $nuevoEmail = $_POST['nuevoEmail'];
    $nuevoTelefonoMovil = $_POST['nuevoTelefonoMovil'];

    // Obtener la ruta de la imagen existente
    $stmtImagen = $dbh->prepare("SELECT IMAGEN FROM estudiante WHERE id = ?");
    $stmtImagen->execute([$idEstudiante]);
    $imagenExistente = $stmtImagen->fetchColumn();

    // Verificar si se proporcionó una nueva imagen
    if (!empty($_FILES['nuevaImagen']['name'])) {
        $nuevaImagen = $_FILES['nuevaImagen']['name'];
        $imagenTemporal = $_FILES['nuevaImagen']['tmp_name'];
        $rutaImagen = "images/" . $nuevaImagen;

        // Mover la nueva imagen a la carpeta de images
        move_uploaded_file($imagenTemporal, $rutaImagen);
    } else {
        // Conservar la imagen existente si no se proporcionó una nueva
        $rutaImagen = $imagenExistente;
    }

    // Realizar la actualización en la base de datos
    $stmt = $dbh->prepare("UPDATE estudiante SET numero_expediente = ?, nif = ?, nombre = ?, apellido1 = ?, apellido2 = ?, email = ?, telefono_movil = ?, imagen = ? WHERE id = ?");
    $stmt->execute([$nuevoNumeroExpediente, $nuevoNif, $nuevoNombre, $nuevoApellido1, $nuevoApellido2, $nuevoEmail, $nuevoTelefonoMovil, $rutaImagen, $idEstudiante]);


    header("Location: index.php");
} else {
    // Obtener el estudiante a editar
    $idEstudiante = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM estudiante WHERE id = ?");
    $stmt->execute([$idEstudiante]);
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Estudiante</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Estudiante</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="idEstudiante" value="<?php echo $estudiante['id']; ?>">

            <div class="form-group">
                <label for="nuevoNumeroExpediente">Número de Expediente:</label>
                <input type="text" id="nuevoNumeroExpediente" name="nuevoNumeroExpediente" class="form-control" value="<?php echo $estudiante['numero_expediente']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoNif">NIF:</label>
                <input type="text" id="nuevoNif" name="nuevoNif" class="form-control" value="<?php echo $estudiante['nif']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoNombre">Nombre:</label>
                <input type="text" id="nuevoNombre" name="nuevoNombre" class="form-control" value="<?php echo $estudiante['nombre']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoApellido1">Apellido 1:</label>
                <input type="text" id="nuevoApellido1" name="nuevoApellido1" class="form-control" value="<?php echo $estudiante['apellido1']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoApellido2">Apellido 2:</label>
                <input type="text" id="nuevoApellido2" name="nuevoApellido2" class="form-control" value="<?php echo $estudiante['apellido2']; ?>">
            </div>

            <div class="form-group">
                <label for="nuevoEmail">Email:</label>
                <input type="email" id="nuevoEmail" name="nuevoEmail" class="form-control" value="<?php echo $estudiante['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevoTelefonoMovil">Teléfono Móvil:</label>
                <input type="text" id="nuevoTelefonoMovil" name="nuevoTelefonoMovil" class="form-control" value="<?php echo $estudiante['telefono_movil']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevaImagen">Nueva Imagen:</label>
                <input type="file" id="nuevaImagen" name="nuevaImagen" class="form-control-file" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>

        <a href="index.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>