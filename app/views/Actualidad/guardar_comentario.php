<?php
header('Content-Type: application/json');
require_once 'ConexionController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = $_POST['comentario'] ?? '';
    $noticia_id = intval($_POST['noticia_id'] ?? 0);
    $fecha = date('Y-m-d');

    if (empty(trim($contenido)) || $noticia_id <= 0) {
        echo json_encode(['success' => false]);
        exit;
    }

    $conexion = (new ConexionController())->conectar();

    $stmt = $conexion->prepare("INSERT INTO comentarios_actualidad (contenido, fechaPublicacion, noticia_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $contenido, $fecha, $noticia_id);

    if ($stmt->execute()) {
        $stmt->close();

        // Obtener comentarios actualizados
        $stmt2 = $conexion->prepare("SELECT contenido, fechaPublicacion FROM comentarios_actualidad WHERE noticia_id = ? ORDER BY id DESC");
        $stmt2->bind_param("i", $noticia_id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $comentarios = $result->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();
        $conexion->close();

        echo json_encode(['success' => true, 'comentarios' => $comentarios]);
        exit;
    } else {
        echo json_encode(['success' => false]);
        exit;
    }
} else {
    echo json_encode(['success' => false]);
    exit;
}
