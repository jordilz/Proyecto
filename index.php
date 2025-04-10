<?php
require_once __DIR__ . '/app/controllers/HomeController.php';

$controller = new HomeController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Redirige al Formulario de contacto con un mensaje
    $message = $controller->sendMessage($_POST['nombre_usuario'], $_POST['correo_usuario'], $_POST['mensaje_usuario']);
    header("Location: /Proyecto/public/formulario_contacto.php?message=" . urlencode($message));
    exit();
} else {
    $controller->index();
}
?>