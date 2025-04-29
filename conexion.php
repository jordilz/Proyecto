<?php
$host = 'localhost';
$db = 'kodonyusu'; 
$user = 'root';
$pass = '';
//Intento de conbexión
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa.";
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
