<?php

class FormularioContactoController {
    public function index() {
        // Use __DIR__ to get the current directory of this file
        include __DIR__ . '/../views/Layouts/header.php';
        include __DIR__ . '/../views/FormularioContacto/index.php';
        include __DIR__ . '/../views/Layouts/footer.php';
    }


    public function sendMessage($nombre, $correo, $mensaje) {
        // Aquí podrías guardar el mensaje en la base de datos o enviar un correo
        // Para simplificar, vamos a simular el envío del mensaje
        if (!empty($nombre) && !empty($correo) && !empty($mensaje)) {
            // Aquí puedes agregar la lógica para enviar el correo o almacenar el mensaje
            return "Mensaje enviado correctamente!";
        } else {
            return "Error: Por favor, complete todos los campos.";
        }
    }
}
?>
