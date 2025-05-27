<?php
include '../includes/db.php';
include '../includes/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Mostrar errores para depuración (puedes quitar esto en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($email) || empty($password)) {
        $mensaje = "<div class='alert alert-warning'>⚠️ Por favor, completa todos los campos.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "<div class='alert alert-danger'>❌ El formato del correo no es válido.</div>";
    } else {
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Asegúrate de que el campo en tu base de datos sea 'contrasena'
            if (password_verify($password, $usuario['contrasena'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                header("Location: ../index.php");
                exit;
            } else {
                $mensaje = "<div class='alert alert-danger'>❌ Contraseña incorrecta.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-danger'>❌ No se encontró una cuenta con ese correo.</div>";
        }

        $stmt->close();
    }
}
?>

<div class="container mt-4">
    <h2>Iniciar sesión</h2>

    <?php echo $mensaje; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
       <div class="d-grid gap-2">
    <button type="submit" class="btn btn-success">Entrar</button>
    <a href="registro.php" class="text-decoration-none text-center mt-2">¿No tienes cuenta? <strong>Regístrate aquí</strong></a>
</div>

    </form>
</div>

<?php include '../includes/footer.php'; ?>
