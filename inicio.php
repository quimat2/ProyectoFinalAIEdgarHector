<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include_once './includes/conectar.php';

if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

if (isset($_POST['cerrar'])) {
    session_destroy();
    header("location: index.php");
    exit;
}

$usuario = $_SESSION['user'];

// Consulta el tipo de usuario y el ID del alumno en la tabla alumnos
$query = "SELECT tipo, alumno_id FROM alumnos WHERE usuario = :usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$tipoUsuario = $row['tipo'];
$alumno_id = $row['alumno_id'];

// Verificar si se ha enviado el formulario de calificación
if (isset($_POST['calificar'])) {
    $tareaID = $_POST['tarea_id'];
    $alumnoID = $_POST['alumno_id'];
    $calificacion = $_POST['calificacion'];

    // Validar que la calificación sea un número válido
    if (!is_numeric($calificacion) || $calificacion < 0 || $calificacion > 10) {
        echo 'Calificación inválida. Debe ser un número entre 0 y 10.';
        exit();
    }

    // Actualizar la calificación en la base de datos
    $query = "UPDATE tareas SET calificacion = :calificacion WHERE tarea_id = :tarea_id AND alumno_id = :alumno_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':calificacion', $calificacion);
    $stmt->bindParam(':tarea_id', $tareaID);
    $stmt->bindParam(':alumno_id', $alumnoID);
    $stmt->execute();

    // Mostrar un mensaje de éxito
    echo 'Calificación guardada exitosamente.';
}

// Verificar si se ha enviado el formulario de eliminación de calificación
if (isset($_POST['eliminar'])) {
    $tareaID = $_POST['tarea_id'];
    $alumnoID = $_POST['alumno_id'];

    // Eliminar la calificación en la base de datos
    $query = "UPDATE tareas SET calificacion = NULL WHERE tarea_id = :tarea_id AND alumno_id = :alumno_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tarea_id', $tareaID);
    $stmt->bindParam(':alumno_id', $alumnoID);
    $stmt->execute();

    // Mostrar un mensaje de éxito
    echo 'Calificación eliminada exitosamente.';
}

// Obtener todas las tareas asignadas al profesor con los alumnos correspondientes
$query = "SELECT tareas.tarea_id, tareas.titulo, tareas.descripcion, tareas.fecha_vencimiento, tareas.calificacion, alumnos.alumno_id, alumnos.nombre AS alumno_nombre
          FROM tareas
          INNER JOIN clases ON tareas.clase_id = clases.clase_id
          INNER JOIN alumnos_clases ON clases.clase_id = alumnos_clases.clase_id
          INNER JOIN alumnos ON alumnos_clases.alumno_id = alumnos.alumno_id
          WHERE clases.profesor_id = :profesor_id AND alumnos.alumno_id = :alumno_id";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':profesor_id', $alumno_id);
$stmt->bindParam(':alumno_id', $alumno_id);
$stmt->execute();
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calificar tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/calificar.css">
</head>
<body>
    <div class="header">
        <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
        <h1>Calificar Tareas</h1>
        <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
    </div>

    <div class="tareas-container">
        <?php foreach ($tareas as $tarea) { ?>
            <div class="tarea-card">
                <h2><?php echo htmlspecialchars($tarea['titulo']); ?></h2>
                <p><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                <p>Fecha de vencimiento: <?php echo htmlspecialchars($tarea['fecha_vencimiento']); ?></p>
                <p>Alumno: <?php echo htmlspecialchars($tarea['alumno_nombre']); ?></p>
                <form action="calificar.php" method="post">
                    <input type="hidden" name="tarea_id" value="<?php echo $tarea['tarea_id']; ?>">
                    <input type="hidden" name="alumno_id" value="<?php echo $tarea['alumno_id']; ?>">
                    <label for="calificacion">Calificación:</label>
                    <input type="number" name="calificacion" step="0.01" min="0" max="10" value="<?php echo $tarea['calificacion']; ?>">
                    <button type="submit" name="calificar">Guardar Calificación</button>
                </form>
                <form action="calificar.php" method="post">
                    <input type="hidden" name="tarea_id" value="<?php echo $tarea['tarea_id']; ?>">
                    <input type="hidden" name="alumno_id" value="<?php echo $tarea['alumno_id']; ?>">
                    <button type="submit" name="eliminar">Eliminar Calificación</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <form action="inicio.php" method="post">
        <input class="logout-button" type="submit" value="Cerrar sesión" name="cerrar">
    </form>

    <form action="inicio.php" method="post">
        <button class="home-button" type="submit" name="volver">
            <i class="fas fa-home"></i>
        </button>
    </form>

</body>
</html>