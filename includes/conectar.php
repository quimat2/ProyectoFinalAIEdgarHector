<?php
$server = 'localhost';
$port = 8889;
$user = 'root';
$password = 'root';
$db = 'proyectofinalai';

try {
    $dsn = "mysql:host=$server;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $password);
    echo 'Conectado';
} catch (PDOException $e) {
    print 'Error!!! ' . $e->getMessage() . '<br />';
    die();
}
?>