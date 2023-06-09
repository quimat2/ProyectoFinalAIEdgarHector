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

// Consulta el tipo de usuario y el ID del profesor en la tabla alumnos
$query = "SELECT tipo, alumno_id FROM alumnos WHERE usuario = :usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$tipoUsuario = $row['tipo'];
$profesor_id = $row['alumno_id'];

// Agregar clase
if (isset($_POST['agregar'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Insertar la nueva clase en la base de datos
    $query = "INSERT INTO clases (nombre, descripcion, profesor_id) VALUES (:nombre, :descripcion, :profesor_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nombre', $titulo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':profesor_id', $profesor_id);
    $stmt->execute();

    // Redirigir a la página actual para evitar envío de formularios duplicados
    header("location: agregar.php");
    exit;
}


// Eliminar clase
if (isset($_POST['eliminar'])) {
    $clase_id = $_POST['clase_id'];

    // Eliminar la clase de la base de datos
    $query = "DELETE FROM clases WHERE clase_id = :clase_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':clase_id', $clase_id);
    $stmt->execute();

    // Redirigir a la página actual después de eliminar la clase
    header("location: agregar.php");
    exit;
}

// Obtener la lista de clases
$query = "SELECT * FROM clases";
$stmt = $pdo->prepare($query);
$stmt->execute();
$clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bienvenido a la página de clases</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./css/agregar.css">
</head>
<body>
  <div class="header">
    <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
    <h1>Bienvenido a la página de clases</h1>
    <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
  </div>

  <div class="clases-container">
    <div class="agregar-clase-card">
      <h2>Agregar Clase</h2>
      <form action="agregar.php" method="post">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descripcion" placeholder="Descripción" required></textarea>
        <button class="agregar-button" type="submit" name="agregar">Agregar</button>
      </form>
    </div>

    <?php foreach ($clases as $clase) { ?>
      <div class="clase-card">
        <h2><?php echo htmlspecialchars($clase['nombre']); ?></h2>
        <p><?php echo htmlspecialchars($clase['descripcion']); ?></p>
        <?php if ($tipoUsuario == 'profesor') { ?>
            <form action="agregar.php" method="post">
              <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
              <button class="delete-button" type="submit" name="eliminar"><i class="fas fa-trash-alt"></i></button>
            </form>
          </div>
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