<?php

class Formulario_Controller {
    public function index() {
        // Incluir la vista del formulario de contacto
        include 'app/views/formulario_contacto.php';
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
