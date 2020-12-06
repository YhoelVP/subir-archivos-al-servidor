<?php
$conexion = 'mysql:host=localhost;dbname=php_imgpdo';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($conexion, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOExceotion $e) {
    echo $e->getMessage();
}