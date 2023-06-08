<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './includes/conectar.php';

if (isset($_SESSION['user'])) {
  header("location:inicio.php");
  exit;
}

// Registro de usuario
if (isset($_POST['registro'])) {
    $user = $_POST['user'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    if (empty($user) || empty($password) || empty($nombre) || empty($tipo)) {
        echo 'Los campos están vacíos';
    } else {
        $consulta = $pdo->prepare('SELECT * FROM alumnos WHERE usuario = :usuario');
        $consulta->bindParam(':usuario', $user);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            echo 'El usuario ya existe';
        } else {
            $insertar = $pdo->prepare('INSERT INTO alumnos (alumno_id, usuario, password, nombre, tipo) VALUES (NULL, :usuario, :password, :nombre, :tipo)');
            $insertar->bindParam(':usuario', $user);
            $insertar->bindParam(':password', $password);
            $insertar->bindParam(':nombre', $nombre);
            $insertar->bindParam(':tipo', $tipo);

            if ($insertar->execute()) {
                echo '¡Felicidades, has sido registrado correctamente!';
                header("Location: inicio.php");
                exit;
            } else {
                echo 'No pudiste ser registrado :(';
            }
        }
    }
}

session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página de registro</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-image: url("./img/registro.jpg");
      background-size: cover;
      font-family: Arial, sans-serif;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-box {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 5px;
      text-align: center;
    }

    .register-box input[type="text"],
    .register-box input[type="password"],
    .register-box select {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: none;
      border-radius: 3px;
    }

    .register-box input[type="submit"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 3px;
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .register-box input[type="submit"]:hover {
      background-color: #388E3C;
    }

    .login-link {
      margin-top: 10px;
    }

    .login-link a {
      color: blue;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="register-box">
      <h2>Registro</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <input type="text" name="user" placeholder="Usuario" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <select name="tipo" required>
          <option value="" disabled selected>Selecciona una opción</option>
          <option value="profesor">Profesor</option>
          <option value="alumno">Alumno</option>
        </select><br>
        <input type="submit" name="registro" value="Registrarse">
      </form>
      <div class="login-link">
        ¿Ya tienes una cuenta? <a href="./index.php">Inicia sesión</a>
      </div>
    </div>
  </div>
</body>
</html>