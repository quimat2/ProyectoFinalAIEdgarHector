<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './includes/conectar.php';

if (isset($_SESSION['user'])) {
  header("Location: inicio.php");
  exit;
}

if (isset($_POST['iniciar'])) {
  $user = $_POST['user'];
  $password = $_POST['password'];

  if (empty($user) || empty($password)) {
      echo 'Los campos están vacíos';
  } else {
      $consulta = $pdo->prepare('SELECT * FROM alumnos WHERE usuario = :usuario AND password = :password');
      $consulta->bindParam(':usuario', $user);
      $consulta->bindParam(':password', $password);
      $consulta->execute();

      $registrado = $consulta->rowCount();

      if ($registrado == 1) {
          $_SESSION['user'] = $user;
          header("Location: inicio.php");
          exit;
      } else {
          echo "Usuario o contraseña incorrecto";
      }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página de inicio de sesión</title>
  <link rel="stylesheet" href="./css/index.css">
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h2>Iniciar sesión</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="user" placeholder="Usuario" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <input type="submit" name="iniciar" value="Ingresar">
      </form>
      <div class="signup-link">
        ¿No tienes una cuenta? <a href="./registro.php">Regístrate</a>
      </div>
    </div>
  </div>
</body>
</html>