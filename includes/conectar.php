<?php
$server = 'localhost';
$port = 3306;
$user = 'root';
$password = 'root';
$db = 'plataforma';

try {
    $dsn = "mysql:host=$server;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $password);
    echo 'Conectado';
} catch (PDOException $e) {
    print 'Error!!! ' . $e->getMessage() . '<br />';
    die();
}
?>