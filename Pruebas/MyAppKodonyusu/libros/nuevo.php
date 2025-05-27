<?php
include '../includes/db.php';
include '../includes/header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mensaje = "";

// Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);
    $productora = trim($_POST["productora"]);
    $fechaLanzamiento = $_POST["fechaLanzamiento"];

    $nombre_imagen = null;

    // Subida de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $directorio = "../uploads/";

        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $nombre_original = basename($_FILES['imagen']['name']);
        $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
        $nombre_imagen = uniqid() . "." . $extension;
        $ruta_destino = $directorio . $nombre_imagen;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $mensaje = "<div class='alert alert-danger'>Error al subir la imagen.</div>";
            $nombre_imagen = null;
        }
    }

    // Insertar la serie
    $sql = "INSERT INTO serie (titulo, descripcion, fechaLanzamiento, productora, portada) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $titulo, $descripcion, $fechaLanzamiento, $productora, $nombre_imagen);

    if ($stmt->execute()) {
        $id_serie = $conexion->insert_id;

        // Insertar etiquetas seleccionadas
        if (!empty($_POST['etiquetas'])) {
            foreach ($_POST['etiquetas'] as $id_etiqueta) {
                $stmt_etiqueta = $conexion->prepare("INSERT INTO serie_etiqueta (serieID, etiquetaID) VALUES (?, ?)");
                $stmt_etiqueta->bind_param("ii", $id_serie, $id_etiqueta);
                $stmt_etiqueta->execute();
            }
        }

        $mensaje = "<div class='alert alert-success'>Serie añadida correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al guardar la serie: " . $stmt->error . "</div>";
    }
}
?>

<div class="container mt-4">
    <h2>Añadir nueva serie</h2>
    <?php echo $mensaje; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Productora</label>
            <input type="text" name="productora" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Fecha de lanzamiento</label>
            <input type="date" name="fechaLanzamiento" class="form-control" required>
        </div>
       <div class="mb-3">
    <label>Etiquetas</label><br>
    <?php
    $resultado = $conexion->query("SELECT * FROM etiquetas");

    if ($resultado && $resultado->num_rows > 0) {
        while ($et = $resultado->fetch_assoc()) {
            echo "<div class='form-check form-check-inline'>";
            echo "<input class='form-check-input' type='checkbox' name='etiquetas[]' value='{$et['id']}' id='et{$et['id']}'>";
            echo "<label class='form-check-label' for='et{$et['id']}'>{$et['nombre']}</label>";
            echo "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>No hay etiquetas disponibles.</div>";
    }
    ?>
</div>
        <div class="mb-3">
            <label>Imagen de portada</label>
            <input type="file" name="imagen" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        <button type="submit" class="btn btn-primary">Guardar serie</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

