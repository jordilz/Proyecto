<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../controllers/Conexion_Controller.php';

$conexionController = new ConexionController();
$conexion = $conexionController->conectar();

$sql = "SELECT nombre, imagen, precio, descuento FROM videojuego";
$resultado = $conexion->query($sql);
?>

<div class="game-grid">
    <?php while ($juego = $resultado->fetch_assoc()) : ?>
        <div class="game-card">
            <img src="<?= htmlspecialchars($juego['imagen']) ?>" alt="<?= htmlspecialchars($juego['nombre']) ?>">
            <div class="discount"><?= htmlspecialchars($juego['descuento']) ?>%</div>
            <h3><?= htmlspecialchars($juego['nombre']) ?></h3>
            <p><?= number_format($juego['precio'], 2) ?> €</p>
            <button class="buy-button">Añadir a la cesta</button>
        </div>
    <?php endwhile; ?>
</div>

<?php $conexion->close(); ?>
