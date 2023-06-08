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
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url("./img/index.png");
      background-size: cover;
      font-family: Arial, sans-serif;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 5px;
      text-align: center;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: none;
      border-radius: 3px;
    }

    .login-box input[type="submit"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 3px;
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .login-box input[type="submit"]:hover {
      background-color: #388E3C;
    }

    .signup-link a {
      color: blue; 
      text-decoration: none;
    }
  </style>
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