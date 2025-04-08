<!-- conexion base de datos -->
<?php

// Detalles de la conexión a la base de datos
$host = 'localhost'; // Database host
$dbname = 'kodonyusu'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectado a la base de datos correctamente.";
} catch (PDOException $e) {
    // Handle connection error
    echo "error, no se ha podido conectar a la base de datos" . $e->getMessage();
}
?>