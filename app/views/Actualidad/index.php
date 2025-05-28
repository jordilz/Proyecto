<div class="news-section">
    <div class="news-section">
        <div class="news-item">
            <img src="noticia1.jpg" alt="Noticia 1">
            <h2>Última Noticia</h2>
            <p>Contenido de la noticia...</p>
            <button class="formulario-button">Comentar</button>

            <form class="formulario-actualidad" method="POST" action="guardar_comentario.php" style="display:none;">
                <textarea name="comentario" placeholder="Escribe tu comentario" required></textarea>
                <input type="hidden" name="noticia_id" value="1"> <!-- Puedes usar IDs reales si tienes -->
                <button class="formulario-button" type="submit">Enviar</button>
            </form>
        </div>
    </div>
    <div class="news-section">
        <div class="news-item">
            <img src="noticia1.jpg" alt="Noticia 1">
            <h2>Última Noticia</h2>
            <p>Contenido de la noticia...</p>
            <button class="formulario-button">Comentar</button>

            <form class="formulario-actualidad" method="POST" action="guardar_comentario.php" style="display:none;">
                <textarea name="comentario" placeholder="Escribe tu comentario" required></textarea>
                <input type="hidden" name="noticia_id" value="1"> <!-- Puedes usar IDs reales si tienes -->
                <button class="formulario-button" type="submit">Enviar</button>
            </form>
        </div>
    </div>
    <div class="news-section">
        <div class="news-item">
            <img src="noticia1.jpg" alt="Noticia 1">
            <h2>Última Noticia</h2>
            <p>Contenido de la noticia...</p>
            <button class="formulario-button">Comentar</button>

            <form class="formulario-actualidad" method="POST" action="guardar_comentario.php" style="display:none;">
                <textarea name="comentario" placeholder="Escribe tu comentario" required></textarea>
                <input type="hidden" name="noticia_id" value="1"> <!-- Puedes usar IDs reales si tienes -->
                <button class="formulario-button" type="submit">Enviar</button>
            </form>
        </div>
    </div>
</div>



<?php
// Conexión base de datos
require_once 'controllers/ConexionController.php';

class ComentariosController {
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenido = $_POST['comentario'] ?? '';
            $fecha = date('Y-m-d');

            if (!empty(trim($contenido))) {
                $conexion = (new ConexionController())->conectar();

                $stmt = $conexion->prepare("INSERT INTO comentarios_actualidad (contenido, fechaPublicacion) VALUES (?, ?)");
                $stmt->bind_param("ss", $contenido, $fecha);

                if ($stmt->execute()) {
                    header("Location: index.php?url=actualidad/index");
                    exit();
                } else {
                    echo "Error al guardar el comentario.";
                }

                $stmt->close();
                $conexion->close();
            } else {
                echo "El comentario no puede estar vacío.";
            }
        } else {
            echo "Método no permitido.";
        }
    }
}
?>
