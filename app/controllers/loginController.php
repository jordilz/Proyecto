<?php
require_once 'Conexion_Controller.php';
require_once '../model/userModel.php';

if (session_status() == PHP_SESSION_NONE) session_start();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($email) || empty($password)) {
        $mensaje = "<div class='alert alert-warning'>⚠️ Por favor, completa todos los campos.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "<div class='alert alert-danger'>❌ El formato del correo no es válido.</div>";
    } else {
        $conexionController = new ConexionController();
        $conexion = $conexionController->conectar();

        $usuarioModel = new UsuarioModel($conexion);
        $usuario = $usuarioModel->obtenerUsuarioPorEmail($email);

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header("Location: ../../index.php");
            exit;
        } else {
            $_SESSION['mensaje'] = "<div class='alert alert-danger'>❌ Credenciales incorrectas.</div>";
            header("Location: ../views/users/login.php");
            exit;
        }
    }
}
