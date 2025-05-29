<?php 
if (session_status() === PHP_SESSION_NONE) session_start();

include '../../views/Layouts/header.php'; 

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<div class="loginContenedor">
    <h2>Iniciar sesión</h2>

    <?php echo $mensaje ?? ''; ?>

    <form method="POST" action="../../controllers/loginController.php" class="mt-3">
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">Entrar</button>
            <a href="registro.php" class="text-decoration-none text-center mt-2">
                ¿No tienes cuenta? <strong>Regístrate aquí</strong>
            </a>
        </div>
    </form>
</div>
<?php include '../../views/Layouts/footer.php'; ?>