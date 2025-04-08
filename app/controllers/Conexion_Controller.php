<!-- conexion base de datos -->
<?php

// Datos de la conexión a la base de datos
$host = 'localhost'; 
$dbname = 'kodonyusu'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado a la base de datos correctamente.";
} catch (PDOException $e) {
    // error de conexión
    echo "error, no se ha podido conectar a la base de datos" . $e->getMessage();
}
?>