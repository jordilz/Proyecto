<?php
require_once 'Conexion_Controller.php';
require_once '../model/userModel.php';

if (session_status() == PHP_SESSION_NONE) session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = (new ConexionController)->conectar();
    $usuarioModel = new UsuarioModel($conexion);

    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmar_password = $_POST["confirmar_password"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Correo no válido.</div>";
    } elseif ($password !== $confirmar_password) {
        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
    } elseif (strlen($password) < 6) {
        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Mínimo 6 caracteres.</div>";
    } elseif ($usuarioModel->existeEmail($email)) {
        $_SESSION['mensaje'] = "<div class='alert alert-warning'>Correo ya registrado.</div>";
    } elseif ($usuarioModel->registrar($nombre, $email, $password)) {
        // Obtener los datos insertados
        $usuario = $usuarioModel->obtenerUsuarioPorEmail($email);

        // Crear sesión automáticamente
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];

        header("Location: ../../index.php");
        exit;
    } else {
        $_SESSION['mensaje'] = "<div class='alert alert-danger'>Error al registrar. Inténtalo de nuevo.</div>";
    }

    header("Location: ../views/users/registro.php");
    exit;
}