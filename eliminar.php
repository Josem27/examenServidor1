<?php
session_start();

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Obtener el ID del estudiante a eliminar
    $idEstudiante = $_GET['id'];

    // Realizar la eliminación en la base de datos
    $stmtEliminar = $dbh->prepare("DELETE FROM estudiante WHERE id = ?");
    $stmtEliminar->execute([$idEstudiante]);

    echo "El estudiante se eliminó correctamente.";
    header("Location: index.php");
    exit();
}
?>
