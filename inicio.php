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

// Consulta el tipo de usuario en la tabla alumnos
$query = "SELECT tipo FROM alumnos WHERE usuario = :usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':usuario', $usuario);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$tipoUsuario = $row['tipo'];

?>

<!DOCTYPE html>
<html>
<head>
  <title>Bienvenido a tu escuela virtual</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="./css/inicio.css">
</head>
<body>
  <div class="header">
    <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
    <h1>Bienvenido a tu escuela virtual</h1>
    <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
  </div>

  <div class="cards">
    <a href="editar.php" class="card">
        <i class="fas fa-user-edit"></i>
        <h2 class="card-title">Editar perfil</h2>
        <p class="card-description">Actualiza tus datos personales</p>
    </a>
    <?php if ($tipoUsuario == 'profesor') { ?>
      <a href="agregar.php" class="card">
        <i class="fas fa-plus-circle"></i>
        <h2 class="card-title">Añadir clase</h2>
        <p class="card-description">Crea una nueva clase</p>
      </a>

      <a href="añadir_tareas.php" class="card"> 
        <i class="fas fa-tasks"></i>
        <h2 class="card-title">Añadir tareas</h2>
        <p class="card-description">Crea nuevas tareas para tus estudiantes</p>
      </a>

      <a href="añadir_calificaciones.php" class="card"> 
        <i class="fas fa-edit"></i>
        <h2 class="card-title">Añadir calificaciones</h2>
        <p class="card-description">Agrega calificaciones para tus estudiantes</p>
      </a>
    <?php } else { ?>
      <a href="unirse.php" class="card">
        <i class="fas fa-user-plus"></i>
        <h2 class="card-title">Unirse a clase</h2>
        <p class="card-description">Únete a una clase existente</p>
      </a>

      <a href="tareas.php" class="card">
        <i class="fas fa-clipboard-list"></i>
        <h2 class="card-title">Tareas</h2>
        <p class="card-description">Consulta tus tareas</p>
      </a>

      <a href="calificaciones.php" class="card">
        <i class="fas fa-chart-bar"></i>
        <h2 class="card-title">Calificaciones</h2>
        <p class="card-description">Revisa tus calificaciones</p>
      </a>
    <?php } ?>
  </div>

  <form action="inicio.php" method="post">
    <input class="logout-button" type="submit" value="Cerrar sesión" name="cerrar">
  </form>
</body>
</html>