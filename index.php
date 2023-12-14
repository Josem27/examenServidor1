<?php
session_start();

require_once 'config.php';

$dbh = include 'config.php';

if ($dbh === null) {
    die("Error: La conexión a la base de datos es nula.");
}

// Función para generar el PDF con TCPDF
function generarPDF($dbh) {
    // Incluir los archivos de TCPDF localmente
    require_once 'tcpdf/tcpdf.php';

    // Crear una instancia de TCPDF con orientación apaisada
    $pdf = new TCPDF('L');

    // Establecer la ubicación de las fuentes de TCPDF
    $fontPath = 'ruta/a/la/carpeta/fonts/';
    TCPDF_FONTS::addTTFfont($fontPath . 'arial.ttf', 'TrueTypeUnicode', '', 32);

    // Crear PDF con TCPDF
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 12);
    $pdf->Cell(0, 10, 'Listado de Estudiantes', 0, 1, 'C');

    $pdf->SetFont('times', '', 10);
    $pdf->Cell(30, 10, 'Número Expediente', 1);
    $pdf->Cell(20, 10, 'NIF', 1);
    $pdf->Cell(30, 10, 'Nombre', 1);
    $pdf->Cell(30, 10, 'Apellido 1', 1);
    $pdf->Cell(30, 10, 'Apellido 2', 1);
    $pdf->Cell(40, 10, 'Email', 1);
    $pdf->Cell(30, 10, 'Teléfono Móvil', 1);
    $pdf->Cell(40, 10, 'Imagen', 1);
    $pdf->Ln();

    // Obtener todos los registros de la tabla 'estudiante'
    $stmtRegistros = $dbh->prepare("SELECT numero_expediente, nif, nombre, apellido1, apellido2, email, telefono_movil, imagen FROM estudiante");
    $stmtRegistros->execute();
    $registros = $stmtRegistros->fetchAll(PDO::FETCH_ASSOC);

    foreach ($registros as $registro) {
        $pdf->Cell(30, 10, $registro['numero_expediente'], 1);
        $pdf->Cell(20, 10, $registro['nif'], 1);
        $pdf->Cell(30, 10, $registro['nombre'], 1);
        $pdf->Cell(30, 10, $registro['apellido1'], 1);
        $pdf->Cell(30, 10, $registro['apellido2'], 1);
        $pdf->Cell(40, 10, $registro['email'], 1);
        $pdf->Cell(30, 10, $registro['telefono_movil'], 1);
        // Reducir el tamaño de la imagen para ajustar al espacio disponible
        $pdf->Cell(40, 10, $registro['imagen'], 1);
        $pdf->Ln();
    }

    // Descargar el PDF
    $pdf->Output('Listado_Estudiantes.pdf', 'D');
    exit(); // Agregar esta línea para detener la ejecución del resto del script
}

// Verificar si se hizo clic en el botón de generar PDF
if (isset($_POST['generar_pdf'])) {
    generarPDF($dbh);
}

// Configuración de la paginación
$registrosPorPagina = 5; // Número de registros por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Obtener el total de estudiantes
$stmtTotalEstudiantes = $dbh->query("SELECT COUNT(*) FROM estudiante");
$totalEstudiantes = $stmtTotalEstudiantes->fetchColumn();

// Obtener la lista de estudiantes paginada
$stmtEstudiantes = $dbh->prepare("SELECT id, email, apellido1, apellido2, nombre, imagen FROM estudiante LIMIT $offset, $registrosPorPagina");
$stmtEstudiantes->execute();
$estudiantes = $stmtEstudiantes->fetchAll(PDO::FETCH_ASSOC);

// Calcular el número total de páginas
$totalPaginas = ceil($totalEstudiantes / $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Estudiantes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Listado de Estudiantes</h2>

        <!-- Botones -->
        <div class="mb-3">
            <a href="registrar.php" class="btn btn-success">Registrar Estudiante</a>
            <form method="post" style="display: inline-block;">
                <button type="submit" name="generar_pdf" class="btn btn-primary">Generar PDF</button>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Apellido 1</th>
                    <th>Apellido 2</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $estudiante) { ?>
                    <tr>
                        <td><?php echo $estudiante['email']; ?></td>
                        <td><?php echo $estudiante['apellido1']; ?></td>
                        <td><?php echo $estudiante['apellido2']; ?></td>
                        <td><?php echo $estudiante['nombre']; ?></td>
                        <td>
                            <?php if (!empty($estudiante['imagen'])) { ?>
                                <img src="<?php echo $estudiante['imagen']; ?>" alt="Imagen del estudiante" style="max-width: 50px; max-height: 50px;">
                            <?php } ?>
                        </td>
                        <td>
                            <a href="editar.php?id=<?php echo $estudiante['id']; ?>">Editar</a> |
                            <a href="eliminar.php?id=<?php echo $estudiante['id']; ?>" onclick="return confirm('¿Estás seguro?')">Eliminar</a> |
                            <a href="detalles.php?id=<?php echo $estudiante['id']; ?>">Detalles</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                    <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
