<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

if (isset($_POST['cerrar'])) {
    session_destroy();
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Bienvenido a tu escuela virtual</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    .logo {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 150px;
      height: 150px; 
    }

    .logo img {
      max-width: 100%;
      max-height: 100%;
      width: auto;
      height: auto;
    }

    .welcome {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .cards {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      padding: 20px;
    }

    .card {
      width: 200px;
      height: 200px;
      background-color: #f4f4f4;
      border-radius: 5px;
      margin: 10px;
      padding: 20px;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .card:hover {
      background-color: #ddd;
    }

    .card i {
      font-size: 48px;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .card-description {
      font-size: 14px;
    }

    .logout-button {
      display: block;
      width: 120px;
      height: 40px;
      margin: 20px auto;
      background-color: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .logout-button:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="./img/logo.png" alt="Logo de la escuela" class="logo">
    <h1>Bienvenido a tu escuela virtual</h1>
    <p class="welcome">Bienvenido: <?php echo htmlspecialchars($_SESSION['user']); ?></p>
  </div>
  
  <div class="cards">
    <?php if ($_SESSION['tipo'] === 'profesor') { ?>
    <a href="añadir_clase.php" class="card">
      <i class="fas fa-plus-circle"></i>
      <h2 class="card-title">Añadir clase</h2>
      <p class="card-description">Crea una nueva clase</p>
    </a>
    <?php } ?>
    <a href="editar_perfil.php" class="card">
      <i class="fas fa-user-edit"></i>
      <h2 class="card-title">Editar perfil</h2>
      <p class="card-description">Actualiza tus datos personales</p>
    </a>
    <?php if ($_SESSION['tipo'] === 'alumno') { ?>
    <a href="unirse_a_clase.php" class="card">
      <i class="fas fa-user-plus"></i>
      <h2 class="card-title">Unirse a clase</h2>
      <p class="card-description">Únete a una clase existente</p>
    </a>
    <?php } ?>
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
  </div>

  <form action="inicio.php" method="post">
    <input class="logout-button" type="submit" value="Cerrar sesión" name="cerrar">
  </form>
</body>
</html>