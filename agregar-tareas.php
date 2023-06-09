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

// Obtener la lista de clases del profesor
$query = "SELECT * FROM clases WHERE profesor_id = :profesor_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':profesor_id', $profesor_id);
$stmt->execute();
$clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agregar tarea
if (isset($_POST['agregar'])) {
    $clase_id = $_POST['clase_id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    // Insertar la nueva tarea en la base de datos
    $query = "INSERT INTO tareas (clase_id, titulo, descripcion, fecha_vencimiento) VALUES (:clase_id, :titulo, :descripcion, :fecha_vencimiento)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':clase_id', $clase_id);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':fecha_vencimiento', $fecha_vencimiento);
    $stmt->execute();

    // Redirigir a la página actual para evitar envío de formularios duplicados
    header("location: agregar-tareas.php");
    exit;
}

// Eliminar tarea
if (isset($_POST['eliminar'])) {
    $tarea_id = $_POST['tarea_id'];

    // Eliminar la tarea de la base de datos
    $query = "DELETE FROM tareas WHERE tarea_id = :tarea_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tarea_id', $tarea_id);
    $stmt->execute();

    // Redirigir a la página actual después de eliminar la tarea
    header("location: agregar-tareas.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Agregar Tareas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./css/agregar.css">
</head>
<body>
  <div class="header">
    <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
    <h1>Agregar Tareas</h1>
    <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
  </div>

  <div class="clases-container">
    <?php foreach ($clases as $clase) { ?>
      <div class="clase-card">
        <h2><?php echo htmlspecialchars($clase['nombre']); ?></h2>
        <p><?php echo htmlspecialchars($clase['descripcion']); ?></p>

        <div class="tareas-container">
          <h3>Tareas:</h3>
          <?php
          // Obtener las tareas de la clase
          $clase_id = $clase['clase_id'];
          $query = "SELECT * FROM tareas WHERE clase_id = :clase_id";
          $stmt = $pdo->prepare($query);
          $stmt->bindParam(':clase_id', $clase_id);
          $stmt->execute();
          $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($tareas as $tarea) {
              ?>
            <div class="tarea-card">
              <h4><?php echo htmlspecialchars($tarea['titulo']); ?></h4>
              <p><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
              <p>Fecha de vencimiento: <?php echo htmlspecialchars($tarea['fecha_vencimiento']); ?></p>

              <?php if ($tipoUsuario == 'profesor') { ?>
                <form action="agregar-tareas.php" method="post">
                  <input type="hidden" name="tarea_id" value="<?php echo $tarea['tarea_id']; ?>">
                  <button class="delete-button" type="submit" name="eliminar"><i class="fas fa-trash-alt"></i></button>
                </form>
              <?php } ?>
            </div>
          <?php } ?>
        </div>

        <?php if ($tipoUsuario == 'profesor') { ?>
          <div class="agregar-tarea-card">
            <h3>Agregar Tarea</h3>
            <form action="agregar-tareas.php" method="post">
              <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
              <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
              </div>
              <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
              </div>
              <div class="form-group">
                <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" required>
              </div>
              <button class="agregar-button" type="submit" name="agregar">Agregar</button>
            </form>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

  <form action="index.php" method="post">
    <input class="logout-button" type="submit" value="Cerrar sesión" name="cerrar">
  </form>

  <form action="inicio.php" method="post">
    <button class="home-button" type="submit" name="volver">
      <i class="fas fa-home"></i>
    </button>
  </form>
  
</body>
</html>