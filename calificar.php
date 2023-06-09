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

// Consulta el tipo de usuario y el ID del profesor o alumno en la tabla alumnos
$query = "SELECT tipo, alumno_id FROM alumnos WHERE usuario = :usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$tipoUsuario = $row['tipo'];
$alumno_id = $row['alumno_id'];

if ($tipoUsuario == 'profesor') {
    // Obtener la lista de clases del profesor
    $query = "SELECT * FROM clases WHERE profesor_id = :profesor_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':profesor_id', $alumno_id);
    $stmt->execute();
    $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Obtener la lista de clases del alumno
    $query = "SELECT c.* FROM clases c INNER JOIN alumnos_clases ac ON c.clase_id = ac.clase_id WHERE ac.alumno_id = :alumno_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':alumno_id', $alumno_id);
    $stmt->execute();
    $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calificar tarea (solo para profesores)
if ($tipoUsuario == 'profesor' && isset($_POST['calificar'])) {
    $tarea_id = $_POST['tarea_id'];
    $calificaciones = $_POST['calificacion'];

    // Actualizar la calificación de la tarea en la base de datos
    foreach ($calificaciones as $alumno_id => $calificacion) {
        $query = "UPDATE tareas SET calificacion = :calificacion WHERE tarea_id = :tarea_id AND alumno_id = :alumno_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':calificacion', $calificacion);
        $stmt->bindParam(':tarea_id', $tarea_id);
        $stmt->bindParam(':alumno_id', $alumno_id);
        $stmt->execute();
    }

    // Redirigir a la página actual para evitar envío de formularios duplicados
    header("location: calificar.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Calificar Tareas</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./css/calificar.css">
</head>
<body>
  <header class="header">
    <a href="index.php" class="logo">Mi App</a>
  </header>
  <div class="header">
    <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
    <h1>Bienvenido a la página para calificar</h1>
    <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
  </div>

  <div class="cerrar-sesion">
  <form method="post" action="">
    <input type="submit" name="cerrar" value="Cerrar Sesión">
  </form>
</div>

<?php if ($tipoUsuario == 'profesor') : ?>
  <h3>Seleccione una clase:</h3>
  <ul class="clase-lista">
    <?php foreach ($clases as $clase) : ?>
      <li class="clase-item">
        <div class="clase-nombre"><?php echo $clase['nombre']; ?></div>
        <div>
          <a href="calificar.php?clase_id=<?php echo $clase['clase_id']; ?>">Ver Tareas</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php elseif ($tipoUsuario == 'alumno') : ?>
  <h3>Tus clases:</h3>
  <ul class="clase-lista">
    <?php foreach ($clases as $clase) : ?>
      <li class="clase-item">
        <div class="clase-nombre"><?php echo $clase['nombre']; ?></div>
        <div>
          <a href="calificar.php?clase_id=<?php echo $clase['clase_id']; ?>">Ver Tareas</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php if ($claseSeleccionada) : ?>
    <h3>Tareas de la clase: <?php echo $claseSeleccionada['nombre']; ?></h3>
    <?php if ($tareas) : ?>
      <ul class="tareas-lista">
        <?php foreach ($tareas as $tarea) : ?>
          <li class="tareas-item">
            <div class="tarea-titulo"><?php echo $tarea['titulo']; ?></div>
            <?php if ($tarea['calificacion'] !== null) : ?>
              <div class="tarea-calificacion">
                Calificación: <?php echo $tarea['calificacion']; ?>
              </div>
            <?php else : ?>
              <div class="tarea-calificacion">
                <form method="post" action="">
                  <input type="text" name="calificacion" placeholder="Calificación">
                  <input type="hidden" name="tarea_id" value="<?php echo $tarea['tarea_id']; ?>">
                  <input type="submit" name="calificar" value="Calificar">
                </form>
              </div>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p>No hay tareas disponibles.</p>
    <?php endif; ?>
  <?php endif; ?>

<?php endif; ?>

<?php if ($mensaje) : ?>
  <div class="mensaje"><?php echo $mensaje; ?></div>
<?php endif; ?>
</div>
</body>
</html>