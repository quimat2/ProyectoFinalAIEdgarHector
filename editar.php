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

// Obtener información del alumno
$query = "SELECT * FROM alumnos WHERE usuario = :usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

// Actualizar los datos del alumno
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    $query = "UPDATE alumnos SET nombre = :nombre, password = :password WHERE alumno_id = :alumno_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':alumno_id', $alumno['alumno_id']);
    $stmt->execute();

    // Redirigir a la página de inicio después de guardar los cambios
    header("location: inicio.php");
    exit;
}

// Eliminar al alumno
if (isset($_POST['eliminar'])) {
    $query = "DELETE FROM alumnos WHERE alumno_id = :alumno_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno['alumno_id']);
    $stmt->execute();

    // Cerrar la sesión y redirigir a la página de inicio
    session_destroy();
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar datos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/editar.css">
</head>
<body>
    <div class="header">
        <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
        <h1>Editar datos</h1>
        <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
    </div>

    <div class="form-container">
        <form action="editar.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($alumno['usuario']); ?>" disabled>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($alumno['nombre']); ?>">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($alumno['password']); ?>">
            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($alumno['tipo']); ?>" disabled>
            <label for="alumno_id">ID:</label>
            <input type="text" id="alumno_id" name="alumno_id" value="<?php echo htmlspecialchars($alumno['alumno_id']); ?>" disabled>
            <button class="save-button" type="submit" name="guardar">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
            <button class="delete-button" type="submit" name="eliminar" onclick="return confirm('¿Estás seguro de eliminar tu cuenta?')">
                <i class="fas fa-trash"></i> Eliminar cuenta
            </button>
        </form>
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