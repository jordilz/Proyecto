<?php
require_once __DIR__ . '/../../controllers/Conexion_Controller.php';

function obtenerComentarios($noticia_id)
{
    $conexion = (new ConexionController())->conectar();
    $stmt = $conexion->prepare("SELECT contenido, fechaPublicacion FROM comentarios_actualidad WHERE noticia_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $noticia_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $comentarios = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conexion->close();
    return $comentarios;
}

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = $_POST['comentario'] ?? '';
    $noticia_id = $_POST['noticia_id'] ?? null;
    $fecha = date('Y-m-d');

    if (!empty(trim($contenido)) && $noticia_id) {
        $conexion = (new ConexionController())->conectar();

        $stmt = $conexion->prepare("INSERT INTO comentarios_actualidad (contenido, fechaPublicacion, noticia_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $contenido, $fecha, $noticia_id);

        if ($stmt->execute()) {
            // Cerrar statement y conexión antes de redireccionar
            $stmt->close();
            $conexion->close();

            // Redirigir a la misma URL para evitar reenvío
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $mensaje = "Error al guardar el comentario.";
        }

        $stmt->close();
        $conexion->close();
    } else {
        $mensaje = "Comentario vacío o noticia inválida.";
    }
}
?>



<div class="news-section">
    <!-- Noticia 1 -->
    <div class="news-item">

        <h2>El Renacer del Ocio: Cine y Videojuegos Marcan Tendencia en 2025</h2>
        <img class="img-noticia" src="/Proyecto/public/img/juegos/juego1.jpg" alt="img noticia 1">
        <p>El 2025 ha traído consigo una nueva ola de entusiasmo por el ocio digital y cultural, donde el cine y los
            videojuegos están desempeñando un papel protagónico. Tras años de cambios en los hábitos de consumo, el
            público está más conectado que nunca con las pantallas, pero también más exigente con la calidad y la
            innovación. <br>
            <br>
            En el ámbito cinematográfico, el auge de las salas premium y los estrenos simultáneos en streaming han
            redefinido la experiencia del espectador. Películas como Horizonte Infinito y Crónicas del Futuro han
            demostrado que el público sigue dispuesto a vivir historias intensas y visualmente espectaculares, tanto en
            casa como en la gran pantalla. Además, el regreso de festivales presenciales ha revitalizado la industria,
            fomentando encuentros entre creadores y audiencias. <br>
            <br>

            Por otro lado, los videojuegos han alcanzado un nuevo nivel de relevancia cultural. Con títulos como
            Eclipse: Nexus y Requiem of Steel, los videojuegos no solo entretienen, sino que exploran temas complejos,
            construyen mundos inmersivos y promueven comunidades activas. Plataformas como Steam, PlayStation y Xbox han
            visto un crecimiento constante, especialmente gracias a los juegos independientes y las experiencias
            cooperativas en línea. <br>
            <br>
            El ocio se ha convertido en un espacio de expresión, escape y conexión social. Ya no se trata solo de pasar
            el tiempo, sino de vivir historias, compartir emociones y participar activamente en mundos creados con
            talento y tecnología. <br>

            Cine y videojuegos caminan de la mano hacia un futuro prometedor donde el entretenimiento no tiene límites.
            En un mundo cada vez más digital, la creatividad sigue siendo el motor que impulsa el ocio moderno.
        </p>


        <form method="POST" action="">
            <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea><br>
            <input type="hidden" name="noticia_id" value="1">
            <button type="submit">Enviar Comentario</button>
        </form>

        <div class="comentarios  comentarios-scroll">
            <h3>Comentarios:</h3>
            <?php
            $comentarios = obtenerComentarios(1);
            if (!$comentarios) {
                echo "<p>No hay comentarios aún.</p>";
            } else {
                foreach ($comentarios as $comentario): ?>
                    <div class="comentario">
                        <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                        <small>Publicado el <?= htmlspecialchars($comentario['fechaPublicacion']) ?></small>
                    </div>
                <?php endforeach;
            }
            ?>
        </div>
    </div>

    <!-- Noticia 2 -->
    <div class="news-item">
        <h2>The Last of Us: Una Experiencia Inolvidable en un Mundo Postapocalíptico</h2>
        <img class="img-noticia" src="/Proyecto/public/img/juegos/juego2.jpg" alt="img noticia 2">
        <p>The Last of Us es uno de los videojuegos más aclamados de la última década, desarrollado por Naughty Dog y
            lanzado inicialmente en 2013 para PlayStation. Esta obra maestra combina una narrativa profunda con un mundo
            postapocalíptico devastador, donde la humanidad lucha por sobrevivir tras una pandemia causada por un hongo
            mutante que convierte a las personas en criaturas agresivas.
            <br><br>
            La historia sigue a Joel y Ellie, dos personajes complejos y llenos de matices, que forman un vínculo
            especial mientras atraviesan un Estados Unidos arrasado. Joel, un hombre endurecido por la pérdida y el
            sufrimiento, debe proteger a Ellie, una joven que podría ser la clave para la salvación de la humanidad. Su
            viaje está lleno de peligros, decisiones difíciles y momentos emotivos que exploran temas como la esperanza,
            la redención y la naturaleza humana.
            <br><br>
            Además de su potente guion, The Last of Us destaca por su jugabilidad inmersiva, que combina sigilo, acción
            y exploración, junto con un diseño de sonido y gráficos que elevan la experiencia a otro nivel. La atmósfera
            tensa y realista hace que cada encuentro con enemigos y cada desafío se sientan auténticos y emocionantes.
            <br><br>
            El éxito del juego llevó a la creación de una secuela, The Last of Us Part II, que profundiza aún más en la
            historia y los personajes, generando debates intensos entre los fans por sus giros argumentales y desarrollo
            emocional.
            <br><br>
            The Last of Us no es solo un videojuego; es una obra que redefine el storytelling en este medio, mostrando
            cómo los videojuegos pueden contar historias profundas y conmovedoras que impactan a jugadores de todo el
            mundo.</p>

        <form method="POST" action="">
            <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea><br>
            <input type="hidden" name="noticia_id" value="2">
            <button type="submit">Enviar Comentario</button>
        </form>

        <div class="comentarios  comentarios-scroll">
            <h3>Comentarios:</h3>
            <?php
            $comentarios = obtenerComentarios(2);
            if (!$comentarios) {
                echo "<p>No hay comentarios aún.</p>";
            } else {
                foreach ($comentarios as $comentario): ?>
                    <div class="comentario">
                        <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                        <small>Publicado el <?= htmlspecialchars($comentario['fechaPublicacion']) ?></small>
                    </div>
                <?php endforeach;
            }
            ?>
        </div>
    </div>

    <!-- Noticia 3 -->
    <div class="news-item">
        <h2>Spider-Man: El Héroe que Conquistó el Corazón de Millones</h2>
        <img class="img-noticia" src="/Proyecto/public/img/juegos/juego3.jpg" alt="img noticia 3">
        <p>Spider-Man, uno de los superhéroes más icónicos de Marvel, ha capturado la imaginación y el cariño de
            millones de fans alrededor del mundo desde su creación en 1962 por Stan Lee y Steve Ditko. Peter Parker, un
            joven estudiante de secundaria, obtiene increíbles poderes arácnidos tras ser mordido por una araña
            radiactiva, transformándose en un héroe que combate el crimen en Nueva York mientras enfrenta los retos
            cotidianos de la adolescencia.
            <br><br>
            Lo que hace especial a Spider-Man es su humanidad y vulnerabilidad. A diferencia de otros superhéroes que
            parecen invencibles, Peter enfrenta problemas reales como la pérdida, la responsabilidad, el amor y la
            identidad. Su icónica frase “Un gran poder conlleva una gran responsabilidad” se ha convertido en un mantra
            para los seguidores y un símbolo del sacrificio que implica ser un héroe.
            <br><br>
            A lo largo de los años, Spider-Man ha protagonizado innumerables cómics, series animadas, videojuegos y
            películas que han evolucionado su historia y personajes. Desde las clásicas versiones de Tobey Maguire y
            Andrew Garfield, hasta la más reciente interpretación de Tom Holland, cada adaptación ha aportado un enfoque
            distinto que ha mantenido fresca y relevante la franquicia.
            <br><br>
            Además, Spider-Man ha trascendido la pantalla para convertirse en un ícono cultural, inspirando a
            generaciones a luchar por el bien común y a creer en su propio potencial. Su mezcla de acción, humor y
            emoción hacen que su historia sea accesible para todas las edades.
            <br><br>
            En resumen, Spider-Man no es solo un superhéroe con poderes arácnidos, sino un símbolo de esperanza,
            valentía y la eterna lucha por hacer lo correcto, aun cuando el camino sea difícil.
        </p>

        <form method="POST" action="">
            <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea><br>
            <input type="hidden" name="noticia_id" value="3">
            <button type="submit">Enviar Comentario</button>
        </form>

        <div class="comentarios  comentarios-scroll">
            <h3>Comentarios:</h3>
            <?php
            $comentarios = obtenerComentarios(3);
            if (!$comentarios) {
                echo "<p>No hay comentarios aún.</p>";
            } else {
                foreach ($comentarios as $comentario): ?>
                    <div class="comentario">
                        <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                        <small>Publicado el <?= htmlspecialchars($comentario['fechaPublicacion']) ?></small>
                    </div>
                <?php endforeach;
            }
            ?>
        </div>
    </div>

</div>