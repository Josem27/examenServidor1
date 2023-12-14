<?php
session_start();

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

// Verificar si se proporcionó un ID de estudiante válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: listado_estudiantes.php"); // Cambia a tu página de listado de estudiantes
    exit();
}

// Obtener los detalles del estudiante específico
$idEstudiante = $_GET['id'];
$stmt = $dbh->prepare("SELECT * FROM estudiante WHERE id = ?");
$stmt->execute([$idEstudiante]);
$estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el estudiante existe
if (!$estudiante) {
    header("Location: index.php"); // Cambia a tu página de listado de estudiantes
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Estudiante</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Detalles de Estudiante</h2>

        <div class="card">
            <?php if ($estudiante['imagen'] != null) { ?>
                <img src="<?php echo $estudiante['imagen']; ?>" class="card-img-top img-fluid" alt="Imagen del estudiante">
            <?php } ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $estudiante['nombre'] . ' ' . $estudiante['apellido1'] . ' ' . $estudiante['apellido2']; ?></h5>
                <p class="card-text"><strong>Número de Expediente:</strong> <?php echo $estudiante['numero_expediente']; ?></p>
                <p class="card-text"><strong>NIF:</strong> <?php echo $estudiante['nif']; ?></p>
                <p class="card-text"><strong>Email:</strong> <?php echo $estudiante['email']; ?></p>
                <p class="card-text"><strong>Teléfono Móvil:</strong> <?php echo $estudiante['telefono_movil']; ?></p>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
