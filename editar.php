<?php
session_start();
include_once 'conectar.php';

if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

$user = $_SESSION['user'];
$consulta = $pdo->prepare('SELECT * FROM alumnos WHERE usuario = :usuario');
$consulta->bindParam(':usuario', $user);
$consulta->execute();
$datos = $consulta->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['guardar'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    $actualizar = $pdo->prepare('UPDATE alumnos SET usuario = :usuario, password = :password, nombre = :nombre, tipo = :tipo WHERE usuario = :actual_usuario');
    $actualizar->bindParam(':usuario', $usuario);
    $actualizar->bindParam(':password', $password);
    $actualizar->bindParam(':nombre', $nombre);
    $actualizar->bindParam(':tipo', $tipo);
    $actualizar->bindParam(':actual_usuario', $user);

    if ($actualizar->execute()) {
        $_SESSION['user'] = $usuario;
        header("Location: inicio.php");
        exit;
    } else {
        echo 'Error al actualizar el perfil.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
</head>
<body>
    <h1>Editar Perfil</h1>
    <form action="editar.php" method="post">
        Usuario: <br>
        <input type="text" name="usuario" value="<?php echo $datos['usuario']; ?>" required><br>
        Contraseña: <br>
        <input type="password" name="password" value="<?php echo $datos['password']; ?>" required><br>
        Nombre: <br>
        <input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" required><br>
        Tipo: <br>
        <select name="tipo" id="tipo">
            <option value="profesor" <?php if ($datos['tipo'] == 'profesor') echo 'selected'; ?>>Profesor</option>
            <option value="alumno" <?php if ($datos['tipo'] == 'alumno') echo 'selected'; ?>>Alumno</option>
        </select><br><br>
        <input type="submit" value="Guardar" name="guardar">
        <a href="inicio.php">Cancelar</a>
    </form>
</body>
</html>
