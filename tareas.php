<?php
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

// Obtener la lista de tareas para las clases en las que el alumno está inscrito
$query = "SELECT tareas.*, clases.nombre AS nombre_clase, alumnos.nombre AS profesor_nombre FROM tareas
          INNER JOIN clases ON tareas.clase_id = clases.clase_id
          INNER JOIN alumnos ON clases.profesor_id = alumnos.alumno_id
          INNER JOIN alumnos_clases ON tareas.clase_id = alumnos_clases.clase_id
          WHERE alumnos_clases.alumno_id = :alumno_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':alumno_id', $alumno_id);
$stmt->execute();
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/unirse.css">
</head>
<body>
    <div class="header">
        <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
        <h1>Tareas</h1>
        <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
    </div>

    <div class="clases-container">
        <?php foreach ($tareas as $tarea) { ?>
            <div class="clase-card">
                <h2><?php echo htmlspecialchars($tarea['nombre_clase']); ?></h2>
                <p><strong>Profesor:</strong> <?php echo htmlspecialchars($tarea['profesor_nombre']); ?></p>
                <p><strong>Fecha de vencimiento:</strong> <?php echo htmlspecialchars($tarea['fecha_vencimiento']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                <p><strong>Calificación:</strong> <?php echo htmlspecialchars($tarea['calificacion']); ?></p>
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