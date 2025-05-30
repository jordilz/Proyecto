<?php
include '../../views/Layouts/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<div class="loginContenedor">
    <h2>Registrarse</h2>

    <?php echo $mensaje; ?>

    <form method="POST" action="../../controllers/registroController.php" class="needs-validation">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico:</label>
            <input type="email" name="email" class="form-control" required>
            <div class="invalid-feedback">Introduce un correo válido.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="d-grid gap-2">
            <label for="confirmar_password" class="form-label">Confirmar contraseña:</label>
            <input type="password" name="confirmar_password" class="form-control" required minlength="6">
            <button type="submit" class="btn btn-success">Registrarse</button>
        </div>
    </form>
</div>

<?php include '../../views/Layouts/footer.php'; ?>

<script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const form = this;
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }

        const password = form.querySelector('input[name="password"]');
        const confirmar = form.querySelector('input[name="confirmar_password"]');
        if (password.value !== confirmar.value) {
            e.preventDefault();
            e.stopPropagation();
            alert("Las contraseñas no coinciden.");
        }

        form.classList.add('was-validated');
    }, false);
</script>
