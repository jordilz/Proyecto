<?php
require_once 'conexion.php';  // Asegúrate de tener esta conexión correctamente configurada.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contraseña_original = $_POST['contraseña'] ?? '';
    $fechaRegistro = date('Y-m-d');

    // Inicializar el mensaje de respuesta
    $message = "";

    // Validación de campos vacíos
    if (empty($nombre) || empty($email) || empty($contraseña_original)) {
        $message = "Todos los campos son obligatorios.";
    }
    // Validación de la longitud de la contraseña
    elseif (strlen($contraseña_original) < 6) {
        $message = "La contraseña debe tener al menos 6 caracteres.";
    }
    // Validación del formato de email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "El correo electrónico no tiene un formato válido.";
    } else {
        try {
            // Verificar si el email ya existe en la base de datos
            $sql_check = "SELECT COUNT(*) FROM usuario WHERE email = ?";
            $stmt = $pdo->prepare($sql_check);
            $stmt->execute([$email]);
            $exists = $stmt->fetchColumn();

            if ($exists > 0) {
                $message = "El correo electrónico ya está registrado.";
            } else {
                // Encriptar contraseña y guardar el nuevo usuario en la base de datos
                $contraseña = password_hash($contraseña_original, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO usuario (nombre, email, contraseña, fechaRegistro) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql_insert);
                $stmt->execute([$nombre, $email, $contraseña, $fechaRegistro]);

                $message = "Usuario registrado correctamente.";
            }
        } catch (PDOException $e) {
            $message = "Error al registrar: " . $e->getMessage();
        }
    }

    // Redirigir a la página de resultado con el mensaje de estado
    header("Location: resultado.php?mensaje=" . urlencode($message));
    exit;
}
?>

