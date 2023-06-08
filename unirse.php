<?php
include_once 'conectar.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

$user = $_SESSION['user'];
$consultaAlumno = $pdo->prepare('SELECT * FROM alumnos WHERE usuario = :usuario');
$consultaAlumno->bindParam(':usuario', $user);
$consultaAlumno->execute();
$alumno = $consultaAlumno->fetch(PDO::FETCH_ASSOC);

if ($alumno['tipo'] !== 'alumno') {
    echo "Usted no tiene permisos para unirse a una clase.";
    exit;
}

if (isset($_GET['clase_id'])) {
    $claseId = $_GET['clase_id'];

    
    $consultaUnido = $pdo->prepare('SELECT * FROM alumnos_clases WHERE alumno_id = :alumno_id AND clase_id = :clase_id');
    $consultaUnido->bindParam(':alumno_id', $alumno['id']);
    $consultaUnido->bindParam(':clase_id', $claseId);
    $consultaUnido->execute();
    $unido = $consultaUnido->fetch(PDO::FETCH_ASSOC);

    if ($unido) {
        echo "Ya estás unido a esta clase.";
        exit;
    }

    
    $unirAlumno = $pdo->prepare('INSERT INTO alumnos_clases (alumno_id, clase_id) VALUES (:alumno_id, :clase_id)');
    $unirAlumno->bindParam(':alumno_id', $alumno['id']);
    $unirAlumno->bindParam(':clase_id', $claseId);

    if ($unirAlumno->execute()) {
        echo "Te has unido a la clase correctamente.";
        exit;
    } else {
        echo "Error al unirse a la clase.";
        exit;
    }
}

$consultaClases = $pdo->query('SELECT * FROM clases');
$clases = $consultaClases->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unirse a Clase</title>
</head>
<body>
    <h1>Unirse a Clase</h1>
    <h3>Clases Disponibles:</h3>
    <ul>
        <?php foreach ($clases as $clase) : ?>
            <li>
                <?php echo $clase['nombre_clase']; ?>
                <a href="unirse.php?clase_id=<?php echo $clase['id']; ?>">Unirse</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
