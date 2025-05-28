<div class="espaciador"><p></p></div>

<?php
session_start();
require_once __DIR__ . '/../../controllers/Conexion_Controller.php';

$conexionController = new ConexionController();
$conexion = $conexionController->conectar();



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

<div class="container mt-4">
    <h1 class="mb-4">Series</h1>
    <form method="GET" action="">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

<div class="container my-4">
    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php while ($serie = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if (!empty($serie['portada'])): ?>
                            <img src="/Proyecto/Pruebas/MyAppKodonyusu/uploads/<?php echo htmlspecialchars($serie['portada']); ?>" class="card-img-top">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x400?text=Sin+imagen" class="card-img-top">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($serie['titulo']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($serie['productora']); ?></h6>
                            <p class="card-text"><?php echo htmlspecialchars(substr($serie['descripcion'], 0, 100)); ?>...</p>
                            <p class="card-text"><strong>Comentarios:</strong> <?php echo $serie['total_comentarios']; ?></p>

                            <?php if (isset($serie['media_puntuacion'])): ?>
                                <?php
                                $mediaRedondeada = round($serie['media_puntuacion'], 1);
                                $estrellasLlenas = floor($mediaRedondeada);
                                $estrellas = str_repeat("⭐", $estrellasLlenas);
                                if ($mediaRedondeada - $estrellasLlenas >= 0.5 && $estrellasLlenas < 5) {
                                    $estrellas .= "☆";
                                    $estrellasLlenas++;
                                }
                                $estrellas .= str_repeat("☆", 5 - $estrellasLlenas);
                                ?>
                                <p class="card-text"><strong>Valoración:</strong> <?php echo $estrellas; ?> <small>(<?php echo $mediaRedondeada; ?>/5)</small></p>
                            <?php else: ?>
                                <p class="card-text"><strong>Valoración:</strong> Sin valorar aún</p>
                            <?php endif; ?>

                            <!--<a href="detalle.php?id=<?php echo $serie['id']; ?>" class="btn btn-sm btn-outline-primary mt-2">Más detalles y valorar</a>-->
                            <a href="/Proyecto/app/views/LoMasVisto/detalle.php?id=<?php echo $serie['id']; ?>" class="btn btn-sm btn-outline-primary mt-2">Más detalles y valorar</a>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No hay series que coincidan con la búsqueda.</p>
    <?php endif; ?>
</div>




    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const checkboxes = document.querySelectorAll('.filtro-etiqueta');
    const hiddenInput = document.getElementById('etiquetasSeleccionadas');

    // Función para actualizar el input oculto con las etiquetas seleccionadas
    function updateEtiquetas() {
        const seleccionadas = Array.from(checkboxes)
            .filter(chk => chk.checked)
            .map(chk => chk.value);

        hiddenInput.value = seleccionadas.join(',');
    }

    // Actualiza cada vez que cambia un checkbox
    checkboxes.forEach(chk => {
        chk.addEventListener('change', updateEtiquetas);
    });

    // Asegura que el input oculto se actualice justo antes de enviar el formulario
    form.addEventListener('submit', function (e) {
        updateEtiquetas();
    });

    // Inicializa el valor por si vienen etiquetas marcadas de una búsqueda anterior
    updateEtiquetas();
});
</script>
