<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MyAppKodonyusu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (versión 5.3.0) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-W2g4Yp+jllwXHeJvs3J9L4rD4Eo0p5kHSpuFRw4x3sKQeG4HBa7UHLUfhvZmyY67" crossorigin="anonymous">


    <!-- Opcional: tu CSS personalizado -->
     <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/Pruebas/MyAppkodonyusu/">MyAppKodonyusu</a>

        <!-- Botón hamburguesa para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Enlaces de navegación -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link disabled">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Pruebas/MyAppkodonyusu/usuarios/login.php">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Pruebas/MyAppkodonyusu/usuarios/registro.php">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido principal puede empezar aquí -->
<div class="container mt-4">
