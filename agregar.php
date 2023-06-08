<?php
include_once 'conectar.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

$user = $_SESSION['user'];
$consulta = $pdo->prepare('SELECT * FROM alumnos WHERE usuario = :usuario');
$consulta->bindParam(':usuario', $user);
$consulta->execute();
$datos = $consulta->fetch(PDO::FETCH_ASSOC);

if ($datos['tipo'] !== 'profesor') {
    echo "Usted no tiene autorizacion para Agregar un clase";
    exit;
}

if (isset($_POST['agregar'])) {
    $nombreClase = $_POST['nombre_clase'];
    $descripcion = $_POST['descripcion'];

    $insertar = $pdo->prepare('INSERT INTO clases (nombre_clase, descripcion) VALUES (:nombre_clase, :descripcion)');
    $insertar->bindParam(':nombre_clase', $nombreClase);
    $insertar->bindParam(':descripcion', $descripcion);

    if ($insertar->execute()) {
        echo 'La clase se ha agregado correctamente.';
    } else {
        echo 'Error al agregar la clase.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Clase</title>
</head>
<body>
    <h1>Agregar Clase</h1>
    <form action="agregar.php" method="post">
        Nombre de la Clase: <br>
        <input type="text" name="nombre_clase" required><br>
        Descripción: <br>
        <textarea name="descripcion" rows="4" required></textarea><br><br>
        <input type="submit" value="Agregar" name="agregar">
        <a href="inicio.php">Cancelar</a>
    </form>
</body>
</html>
