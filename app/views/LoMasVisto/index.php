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
        <input type="hidden" name="url" value="LoMasVisto/index">
        
        <!-- Comentado: Input oculto de etiquetas -->
        <!-- 
        <input type="hidden" name="etiquetas" id="etiquetasSeleccionadas" value="<?php echo htmlspecialchars(implode(',', $etiquetas_filtro)); ?>">
        -->

        <div class="row mb-4">

            <!-- Comentado: Dropdown de etiquetas -->
            <!--
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
                </div>
            </div>
            -->

            <!--Barra de búsqueda -->
<div class="col-md-12">
  <div class="row gx-2 align-items-stretch">
    <!-- Input: 90% -->
    <div class="col-10">
      <input 
        type="text" 
        name="buscar" 
        class="form-control h-100 py-2" 
        style="height: 48px; font-size: 2rem;" 
        placeholder="Buscar por título, productora o descripción" 
        value="<?php echo htmlspecialchars(str_replace('%', '', $buscar_texto)); ?>">
    </div>

    <!-- Botón: 10% -->
    <div class="col-2">
      <button 
        class="btn w-100 text-white fw-bold" 
        type="submit" 
        style="height: 48px; background-color: var(--primario); border-radius: 0; font-size: 2rem;">
        Buscar
      </button>
    </div>
  </div>
</div>


            <!-- Comentado: Botón de filtrar adicional -->
            <!--
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
            -->

        </div>
    </form>
</div>



<div class="container my-4">
    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php while ($serie = $result->fetch_assoc()): ?>
    <div class="col">
        <div class="card h-100 text-white" style="background-color: var(--grisTransparente); border: none;">

            <?php if (!empty($serie['portada'])): ?>
                <img src="/Proyecto/Pruebas/MyAppKodonyusu/uploads/<?php echo htmlspecialchars($serie['portada']); ?>" 
                     class="card-img-top" 
                     style="height: 400px; object-fit: cover;">
            <?php else: ?>
                <img src="https://via.placeholder.com/300x400?text=Sin+imagen" 
                     class="card-img-top" 
                     style="height: 400px; object-fit: cover;">
            <?php endif; ?>

            <div class="card-body d-flex flex-column justify-content-between" style="min-height: 200px;">
                
                <!-- Título + Valoración + Comentarios -->
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fs-3 m-0"><?php echo htmlspecialchars($serie['titulo']); ?></h5>
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
                        <div class="text-end fs-6">
                            <div><?php echo $estrellas; ?></div>
                            <div class="text-white-50">(<?php echo $mediaRedondeada; ?>/5)</div>
                            <div class="text-white mt-1"><strong>Comentarios:</strong> <?php echo $serie['total_comentarios']; ?></div>
                        </div>
                    <?php else: ?>
                        <div class="text-end fs-6 text-white-50">
                            Sin valorar aún
                            <div class="text-white mt-1"><strong>Comentarios:</strong> <?php echo $serie['total_comentarios']; ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Productora -->
                <h6 class="card-subtitle mb-2 text-white fs-5"><?php echo htmlspecialchars($serie['productora']); ?></h6>

                <!-- Descripción -->
                <p class="card-text fs-4 mb-1">
                    <?php echo htmlspecialchars(substr($serie['descripcion'], 0, 160)); ?>...
                </p>

                <!-- Botón naranja más alto -->
                <a href="/Proyecto/app/views/LoMasVisto/detalle.php?id=<?php echo $serie['id']; ?>" 
                   class="btn fw-bold text-white w-100 mt-auto py-3 fs-5" 
                   style="background-color: var(--primario); border: none;">
                   Más detalles y valorar
                </a>
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
/*document.addEventListener('DOMContentLoaded', function () {
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
});*/
</script>
