<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include_once __DIR__ . '/../controllers/Conexion_Controller.php';
include_once __DIR__ . '/../model/userModel.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$conexionController = new ConexionController();
$conexion = $conexionController->conectar();
$usuarioModel = new UsuarioModel($conexion);
$id = $_SESSION['usuario_id'];
$usuario = $usuarioModel->obtenerUsuarioPorId($id);
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['actualizar_datos'])) {
        $nombre = trim($_POST["nombre"]);
        $email = trim($_POST["email"]);
        if ($usuarioModel->actualizarPerfil($id, $nombre, $email)) {
            $mensaje = "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
            $_SESSION['usuario_nombre'] = $nombre;
            $usuario = $usuarioModel->obtenerUsuarioPorId($id);
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al actualizar.</div>";
        }
    }

    if (isset($_POST['cambiar_password'])) {
        $password = $_POST["password"];
        $confirmar = $_POST["confirmar_password"];
        if ($password === $confirmar && strlen($password) >= 6) {
            if ($usuarioModel->cambiarContrasena($id, $password)) {
                $mensaje = "<div class='alert alert-success'>Contraseña cambiada.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al cambiar la contraseña.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-warning'>Las contraseñas no coinciden o son muy cortas.</div>";
        }
    }
}
