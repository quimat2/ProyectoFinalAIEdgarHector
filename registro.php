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
  $nombre = $_POST["nombre"];
  $usuario = $_POST["usuario"];
  $contraseña = $_POST["contraseña"];
  $tipo = $_POST["tipo"];

  // Subir la foto de usuario
  $foto = $_FILES["foto"]["name"];
  $foto_tmp = $_FILES["foto"]["tmp_name"];
  move_uploaded_file($foto_tmp, "./img/pfp".$foto);

  // Insertar los datos en la base de datos
  $sql = "INSERT INTO nombre_de_tu_tabla (usuario, password, nombre, foto, tipo)
          VALUES ('$usuario', '$contraseña', '$nombre', '$foto', '$tipo')";

  if ($conn->query($sql) === TRUE) {
    // Registro exitoso
    echo "Registro exitoso";
  } else {
    // Error en el registro
    echo "Error en el registro: " . $conn->error;
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página de registro</title>
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

    .register-box input[type="file"] {
      margin-bottom: 10px;
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
      <form>
        <input type="text" placeholder="Nombre" required><br>
        <input type="text" placeholder="Usuario" required><br>
        <input type="password" placeholder="Contraseña" required><br>
        Foto: 
        <input type="file" accept="image/*"><br>
        <select required>
          <option value="" disabled selected>Selecciona una opción</option>
          <option value="profesor">Profesor</option>
          <option value="alumno">Alumno</option>
        </select><br>
        <input type="submit" value="Registrarse">
      </form>
      <div class="login-link">
        ¿Ya tienes una cuenta? <a href="./index.php">Inicia sesión</a>
      </div>
    </div>
  </div>
</body>
</html>