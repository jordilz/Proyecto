<div class="espaciador"><p></p></div>
<?php include __DIR__ . '/../Layouts/header.php'; ?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../controllers/Conexion_Controller.php';

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
                <img src="/Proyecto/public/img/series/<?php echo htmlspecialchars($serie['portada']); ?>" class='img-fluid mb-3'>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($serie['titulo']); ?></h2>
            <h6 class="text-muted"><?php echo htmlspecialchars($serie['productora']); ?></h6>
            <p><?php echo nl2br(htmlspecialchars($serie['descripcion'])); ?></p>
        </div>
    </div>

    <hr>
    <h4 class="text-white">Comentarios</h4>


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
    ?>

    <div class="mt-4">
        <?php if ($comentarios->num_rows > 0): ?>
            <?php while ($comentario = $comentarios->fetch_assoc()): ?>
                <?php
                    $puntuacion = (int)$comentario['puntuacion'];
                    $estrellas = str_repeat("⭐", $puntuacion) . str_repeat("☆", 5 - $puntuacion);
                ?>
                <div class="card mb-4 text-white" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 12px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($comentario['nombre']); ?></h6>
                            <span class="small text-warning"><?php echo $estrellas; ?></span>
                        </div>
                        <p class="mb-2"><?php echo nl2br(htmlspecialchars($comentario['contenido'])); ?></p>
                        <div class="text-end">
                            <small class="text-white-50"><?php echo $comentario['fechaPublicacion']; ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-secondary text-center text-white" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2);">
                No hay comentarios aún. ¡Sé el primero en opinar!
            </div>
        <?php endif; ?>
    </div>

    <?php echo $mensaje; ?>

<?php if (isset($_SESSION['usuario_id'])): ?>
    <form method="POST" class="mb-4 mt-4" style="background-color: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); color: white;">
        <div class="mb-3">
            <label for="texto" class="form-label" style="font-size: 1.80rem;">Añade un comentario:</label>
            <textarea name="texto" class="form-control" required 
                style="background-color: rgba(255,255,255,0.08); 
                       border: 1px solid rgba(255,255,255,0.2); 
                       color: var(--blanco);"></textarea>
        </div>
        <div class="mb-3">
            <label for="puntuacion" class="form-label" style="font-size: 1.80rem;">Puntuación (1 a 5):</label>
            <select name="puntuacion" class="form-select" required 
                style="background-color: rgba(255,255,255,0.08); 
                       border: 1px solid rgba(255,255,255,0.2); 
                       color: var(--grisClaro);">
                <option value="">Selecciona...</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"
    style="font-size: 1.1rem; padding: 10px 24px; border-radius: 8px;">
    Enviar comentario
</button>

    </form>
<?php else: ?>
    <p><a href="/Proyecto/app/views/users/login.php" class="text-primary">Inicia sesión</a> para comentar.</p>
<?php endif; ?>



<div class="d-flex justify-content-center">
  <a 
    href="/Proyecto/index.php?url=LoMasVisto/index" 
    class="btn text-white fw-bold mb-4" 
    style="height: 40px; background-color: var(--primario); border-radius: 0; font-size: 2rem; padding: 0 2rem;">
    Volver a series
  </a>
</div>

</div>

<?php include __DIR__ . '/../Layouts/footer.php'; ?>
