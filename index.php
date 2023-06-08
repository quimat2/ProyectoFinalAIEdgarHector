<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Conectarse a la base de datos
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "plataforma";
  
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
  }

  // Obtener los datos del formulario
  $usuario = $_POST["usuario"];
  $contraseña = $_POST["contraseña"];

  // Verificar las credenciales de inicio de sesión
  $sql = "SELECT * FROM nombre_de_tu_tabla WHERE usuario = '$usuario' AND password = '$contraseña'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // Inicio de sesión exitoso
    session_start();
    $_SESSION["usuario"] = $usuario;
    header("Location: inicio.php"); // Redireccionar al usuario a la página de inicio
  } else {
    // Credenciales inválidas
    echo "Credenciales inválidas";
  }

  $conn->close();
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
      background-image: url("./img/escuela.png");
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
      <form>
        <input type="text" placeholder="Usuario" required><br>
        <input type="password" placeholder="Contraseña" required><br>
        <input type="submit" value="Ingresar">
      </form>
      <div class="signup-link">
        ¿No tienes una cuenta? <a href="./registro.php">Regístrate</a>
      </div>
    </div>
  </div>
</body>
</html>
