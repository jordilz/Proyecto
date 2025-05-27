<?php
include '../includes/db.php';
include '../includes/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmar_password = $_POST["confirmar_password"];

    // Validación en servidor
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "<div class='alert alert-danger'>Correo electrónico no válido.</div>";
    } elseif ($password !== $confirmar_password) {
        $mensaje = "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
    } elseif (strlen($password) < 6) {
        $mensaje = "<div class='alert alert-danger'>La contraseña debe tener al menos 6 caracteres.</div>";
    } else {
        // Comprobamos si ya existe el correo
        $stmt = $conexion->prepare("SELECT id FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "<div class='alert alert-warning'>Ya existe una cuenta con ese correo.</div>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO usuario (nombre, email, contrasena, fechaRegistro) VALUES (?, ?, ?, CURDATE())");
            $stmt->bind_param("sss", $nombre, $email, $hashedPassword);
            if ($stmt->execute()) {
                $mensaje = "<div class='alert alert-success'>Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al registrar. Inténtalo de nuevo.</div>";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <h2>Registrarse</h2>
    <?php echo $mensaje; ?>
    <form method="POST" class="needs-validation" novalidate>
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
        <div class="mb-3">
            <label for="confirmar_password" class="form-label">Confirmar contraseña:</label>
            <input type="password" name="confirmar_password" class="form-control" required minlength="6">
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</div>

<script>
// Validación del lado del cliente
document.querySelector("form").addEventListener("submit", function (e) {
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

<?php include '../includes/footer.php'; ?>
