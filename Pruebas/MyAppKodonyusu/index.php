<?php
session_start();
include 'includes/db.php';
 include 'includes/header.php';

// Variables base
$etiquetas_filtro = array_filter(
    isset($_GET['etiquetas']) ? explode(',', $_GET['etiquetas']) : [],
    fn($e) => is_numeric($e) && $e !== ''
);
$buscar_texto = isset($_GET['buscar']) ? $_GET['buscar'] : '';

$sql = "
    SELECT s.*, COUNT(c.id) AS total_comentarios, AVG(c.puntuacion) AS media_puntuacion
    FROM serie s
    LEFT JOIN comentario c ON s.id = c.serieID
    WHERE 1
";
$params = [];
$types = '';

if (!empty($etiquetas_filtro)) {
    $placeholders = implode(',', array_fill(0, count($etiquetas_filtro), '?'));
    $sql .= " AND s.id IN (
        SELECT serieID FROM serie_etiqueta WHERE etiquetaID IN ($placeholders)
    )";
    $types .= str_repeat('i', count($etiquetas_filtro));
    $params = array_merge($params, $etiquetas_filtro);
}

if ($buscar_texto != '') {
    $sql .= " AND (s.titulo LIKE ? OR s.descripcion LIKE ? OR s.productora LIKE ?)";
    $types .= 'sss';
    $buscar_texto_like = "%$buscar_texto%";
    array_push($params, $buscar_texto_like, $buscar_texto_like, $buscar_texto_like);
}

$sql .= " GROUP BY s.id ORDER BY s.fechaLanzamiento DESC";

$stmt = $conexion->prepare($sql);
if ($stmt === false) {
    die("Error en prepare: " . $conexion->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Series</title>
    <link rel="stylesheet" href="/MyAppkodonyusu/assets/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!--<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container mt-2 text-end">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <span class="me-3">Hola, <strong><?php echo $_SESSION['usuario_nombre']; ?></strong></span>
            <a href="/MyAppkodonyusu/usuarios/logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        <?php else: ?>
            <a href="/Pruebas/MyAppkodonyusu/usuarios/login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
            <a href="/Pruebas/MyAppkodonyusu/usuarios/registro.php" class="btn btn-light btn-sm">Registrarse</a>
        <?php endif; ?>
    </div>
    <div class="container-fluid">
        <a class="navbar-brand" href="/Pruebas/MyAppkodonyusu/">Kodonyusu</a>
    </div>
</nav>-->

<div class="container mt-4">
    <h1 class="mb-4">Series</h1>
    <form method="GET" action="">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        Filtrar por Etiquetas
                    </button>
                    <ul class="dropdown-menu p-2" style="max-height: 200px; overflow-y: auto;">
                        <?php
                        $etiquetas = $conexion->query("SELECT * FROM etiquetas ORDER BY nombre");
                        while ($etiqueta = $etiquetas->fetch_assoc()) {
                            $checked = in_array($etiqueta['id'], $etiquetas_filtro) ? 'checked' : '';
                            echo "<li>
                                    <div class='form-check'>
                                        <input class='form-check-input filtro-etiqueta' type='checkbox' value='{$etiqueta['id']}' id='etiq{$etiqueta['id']}' $checked>
                                        <label class='form-check-label' for='etiq{$etiqueta['id']}'>
                                            {$etiqueta['nombre']}
                                        </label>
                                    </div>
                                </li>";
                        }
                        ?>
                    </ul>
                    <input type="hidden" name="etiquetas" id="etiquetasSeleccionadas" value="<?php echo htmlspecialchars(implode(',', $etiquetas_filtro)); ?>">
                </div>
            </div>

            <div class="col-md-9">
                <div class="input-group">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por título, productora o descripción" value="<?php echo htmlspecialchars(str_replace('%', '', $buscar_texto)); ?>">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="row">
<?php
if ($result->num_rows > 0):
    while ($serie = $result->fetch_assoc()):
        $mediaRedondeada = null;
        $estrellas = "";

        if (isset($serie['media_puntuacion'])) {
            $mediaRedondeada = round($serie['media_puntuacion'], 1);
            $estrellasLlenas = floor($mediaRedondeada);
            $estrellas = str_repeat("⭐", $estrellasLlenas);
            if ($mediaRedondeada - $estrellasLlenas >= 0.5 && $estrellasLlenas < 5) {
                $estrellas .= "☆";
                $estrellasLlenas++;
            }
            $estrellas .= str_repeat("☆", 5 - $estrellasLlenas);
        }
?>
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <?php if (!empty($serie['portada'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($serie['portada']); ?>" class="card-img-top">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x400?text=Sin+imagen" class="card-img-top">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($serie['titulo']); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($serie['productora']); ?></h6>
                <p class="card-text"><?php echo htmlspecialchars(substr($serie['descripcion'], 0, 100)); ?>...</p>
                <p class="card-text"><strong>Comentarios:</strong> <?php echo $serie['total_comentarios']; ?></p>

                <?php if ($mediaRedondeada !== null): ?>
                    <p class="card-text"><strong>Valoración:</strong> <?php echo $estrellas; ?> <small>(<?php echo $mediaRedondeada; ?>/5)</small></p>
                <?php else: ?>
                    <p class="card-text"><strong>Valoración:</strong> Sin valorar aún</p>
                <?php endif; ?>

                <a href="/Pruebas/MyAppkodonyusu/libros/detalle.php?id=<?php echo $serie['id']; ?>" class="btn btn-sm btn-outline-primary mt-2">Más detalles y valorar</a>
            </div>
        </div>
    </div>
<?php
    endwhile;
else:
?>
    <p>No hay series que coincidan con la búsqueda.</p>
<?php endif; ?>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.filtro-etiqueta');
    const hiddenInput = document.getElementById('etiquetasSeleccionadas');

    function updateEtiquetas() {
        const seleccionadas = [];
        checkboxes.forEach(chk => {
            if (chk.checked) seleccionadas.push(chk.value);
        });
        hiddenInput.value = seleccionadas.join(',');
    }

    checkboxes.forEach(chk => {
        chk.addEventListener('change', updateEtiquetas);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
