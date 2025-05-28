<?php include __DIR__ . '/header.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../controllers/Conexion_Controller.php';

$conexionController = new ConexionController();
$conexion = $conexionController->conectar();

$id_serie = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mensaje = "";

// Procesar comentario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
    $texto = trim($_POST['texto']);
    $puntuacion = (int)$_POST['puntuacion'];
    $usuarioID = $_SESSION['usuario_id'];

    if ($puntuacion >= 1 && $puntuacion <= 5 && !empty($texto)) {
        $stmt = $conexion->prepare("INSERT INTO comentario (usuarioID, serieID, contenido, fechaPublicacion, puntuacion) VALUES (?, ?, ?, CURDATE(), ?)");
        $stmt->bind_param("iisi", $usuarioID, $id_serie, $texto, $puntuacion);
        $stmt->execute();
        $stmt->close();
        $mensaje = "<div class='alert alert-success'>Comentario guardado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Por favor, escribe un comentario y selecciona una puntuación válida (1 a 5).</div>";
    }
}

// Obtener detalles de la serie
$stmt = $conexion->prepare("SELECT * FROM serie WHERE id = ?");
$stmt->bind_param("i", $id_serie);
$stmt->execute();
$resultado = $stmt->get_result();
$serie = $resultado->fetch_assoc();
$stmt->close();

if (!$serie) {
    echo "<div class='alert alert-warning'>Serie no encontrada.</div>";
    include '../includes/footer.php';
    exit;
}
?>

<div class="container mt-4 pt-5" style="margin-top: 140px;">
    <div class="row">
        <div class="col-md-4">
            <?php if (!empty($serie['portada'])): ?>
                <img src="/Proyecto/Pruebas/MyAppKodonyusu/uploads/<?php echo htmlspecialchars($serie['portada']); ?>" class='img-fluid mb-3'>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($serie['titulo']); ?></h2>
            <h6 class="text-muted"><?php echo htmlspecialchars($serie['productora']); ?></h6>
            <p><?php echo nl2br(htmlspecialchars($serie['descripcion'])); ?></p>
        </div>
    </div>

    <hr>
    <h4>Comentarios</h4>

    <?php
    // Media de puntuaciones
    $stmtAvg = $conexion->prepare("SELECT AVG(puntuacion) as media, COUNT(*) as total FROM comentario WHERE serieID = ?");
    $stmtAvg->bind_param("i", $id_serie);
    $stmtAvg->execute();
    $resultAvg = $stmtAvg->get_result()->fetch_assoc();
    $stmtAvg->close();

    if ($resultAvg['total'] > 0) {
        $mediaRedondeada = round($resultAvg['media'], 1);
        $estrellasLlenas = floor($mediaRedondeada);
        $mediaStr = str_repeat("⭐", $estrellasLlenas);
        if ($mediaRedondeada - $estrellasLlenas >= 0.5 && $estrellasLlenas < 5) {
            $mediaStr .= "☆";
            $estrellasLlenas++;
        }
        $mediaStr .= str_repeat("☆", 5 - $estrellasLlenas);
        echo "<p><strong>Valoración media:</strong> $mediaStr <small>($mediaRedondeada / 5, " . $resultAvg['total'] . " votos)</small></p>";
    } else {
        echo "<p><strong>Valoración:</strong> Sin valorar aún</p>";
    }
    ?>

<?php
// Comentarios
$stmt = $conexion->prepare("
    SELECT c.contenido, c.puntuacion, c.fechaPublicacion, u.nombre 
    FROM comentario c 
    JOIN usuario u ON c.usuarioID = u.id 
    WHERE c.serieID = ? 
    ORDER BY c.fechaPublicacion DESC
");
$stmt->bind_param("i", $id_serie);
$stmt->execute();
$comentarios = $stmt->get_result();

if ($comentarios->num_rows > 0) {
    while ($comentario = $comentarios->fetch_assoc()) {
        echo "<div class='card mb-2' style='background-color: #f9f9f9; color: #212529;'>";
        echo "<div class='card-body'>";
        echo "<h6 class='card-subtitle mb-1' style='color: #212529;'>" . htmlspecialchars($comentario['nombre']) . " – Puntuación: " . $comentario['puntuacion'] . "/5</h6>";
        echo "<p class='card-text' style='color: #212529;'>" . nl2br(htmlspecialchars($comentario['contenido'])) . "</p>";
        echo "<small style='color: #212529;'>" . $comentario['fechaPublicacion'] . "</small>";
        echo "</div></div>";
    }
} else {
    echo "<p>No hay comentarios aún.</p>";
}
?>


    <?php echo $mensaje; ?>

    <?php if (isset($_SESSION['usuario_id'])): ?>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="texto" class="form-label">Añade un comentario:</label>
            <textarea name="texto" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="puntuacion" class="form-label">Puntuación (1 a 5):</label>
            <select name="puntuacion" class="form-select" required>
                <option value="">Selecciona...</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enviar comentario</button>
    </form>
    <?php else: ?>
        <p><a href="../usuarios/login.php">Inicia sesión</a> para comentar.</p>
    <?php endif; ?>

    <a href="/Proyecto/lomasvisto.php" class="btn btn-secondary mb-4">Volver a series</a>

</div>

<?php include __DIR__ . '/footer.php'; ?>
