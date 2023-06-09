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

// Unirse a una clase
if (isset($_POST['unirse'])) {
    $clase_id = $_POST['clase_id'];

    // Verificar si el alumno ya está en la clase
    $query = "SELECT * FROM alumnos_clases WHERE alumno_id = :alumno_id AND clase_id = :clase_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->bindParam(':clase_id', $clase_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // Insertar al alumno en la tabla alumnos_clases
        $query = "INSERT INTO alumnos_clases (alumno_id, clase_id) VALUES (:alumno_id, :clase_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':alumno_id', $alumno_id);
        $stmt->bindParam(':clase_id', $clase_id);
        $stmt->execute();
    }

    // Redirigir a la página actual para evitar envío de formularios duplicados
    header("location: unirse.php");
    exit;
}

// Abandonar una clase
if (isset($_POST['abandonar'])) {
    $clase_id = $_POST['clase_id'];

    // Eliminar al alumno de la tabla alumnos_clases
    $query = "DELETE FROM alumnos_clases WHERE alumno_id = :alumno_id AND clase_id = :clase_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->bindParam(':clase_id', $clase_id);
    $stmt->execute();

    // Redirigir a la página actual después de abandonar la clase
    header("location: unirse.php");
    exit;
}

// Obtener la lista de clases con información del profesor
$query = "SELECT clases.*, alumnos.nombre AS profesor_nombre FROM clases
          INNER JOIN alumnos ON clases.profesor_id = alumnos.alumno_id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido a la página de clases</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/unirse.css">
</head>
<body>
    <div class="header">
        <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
        <h1>Bienvenido a la página de clases</h1>
        <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
    </div>

    <div class="clases-container">
        <?php foreach ($clases as $clase) { ?>
            <div class="clase-card">
                <h2><?php echo htmlspecialchars($clase['nombre']); ?></h2>
                <p><?php echo htmlspecialchars($clase['descripcion']); ?></p>
                <p>Profesor: <?php echo htmlspecialchars($clase['profesor_nombre']); ?></p> <!-- Mostrar nombre del profesor -->
                <p>Profesor ID: <?php echo htmlspecialchars($clase['profesor_id']); ?></p> <!-- Mostrar ID del profesor -->
                <?php
                // Verificar si el alumno ya está en la clase
                $query = "SELECT * FROM alumnos_clases WHERE alumno_id = :alumno_id AND clase_id = :clase_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':alumno_id', $alumno_id);
                $stmt->bindParam(':clase_id', $clase['clase_id']);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <?php if (!$row) { ?>
                    <!-- Botón para unirse a la clase -->
                    <form action="unirse.php" method="post">
                        <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
                        <button class="join-button" type="submit" name="unirse">Unirse</button>
                    </form>
                <?php } else { ?>
                    <!-- Botón para abandonar la clase -->
                    <form action="unirse.php" method="post">
                        <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
                        <button class="leave-button" type="submit" name="abandonar">Abandonar</button>
                    </form>
                <?php } ?>
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