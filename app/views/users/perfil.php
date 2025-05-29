<?php
include '../../views/Layouts/header.php';
include '../../controllers/PerfilController.php';
?>

<div class="loginContenedor">
    <h2 class="mb-4 text-center">Mi Perfil</h2>
    <?= $mensaje ?>

    <div class="foto-perfil text-center mb-4">
        <div class="cuadro-perfil">
            <img src="<?= $usuario['foto'] ?? '/Proyecto/public/img/default.png' ?>" alt="Foto de perfil" class="img-fluid">
        </div>
    </div>


    <form method="POST" class="mb-4" id="formPerfil">
        <input type="hidden" name="actualizar_datos" value="1">

        <div class="mb-3">
            <label class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($usuario['nombre']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico:</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($usuario['email']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Nueva contraseña:</label>
            <input type="password" name="password" class="form-control" minlength="6">
        </div>

        <div class="d-grid gap-2">
            <label class="form-label">Confirmar contraseña:</label>
            <input type="password" name="confirmar_password" class="form-control" minlength="6">
            <button type="button" class="btn btn-naranja" data-bs-toggle="modal" data-bs-target="#confirmarCambiosModal">Guardar cambios</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-negro">Cerrar sesión</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmarCambiosModal" tabindex="-1" aria-labelledby="confirmarCambiosLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="confirmarCambiosLabel">Confirmar cambios</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas guardar los cambios realizados?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="confirmarEnvioBtn">Sí, guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("confirmarEnvioBtn").addEventListener("click", function() {
        document.getElementById("formPerfil").submit();
    });
</script>

<?php include '../../views/Layouts/footer.php'; ?>