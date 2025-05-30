<?php

class FormularioContactoController {
    public function index() {
        include __DIR__ . '/../views/Layouts/header.php';
        include __DIR__ . '/../views/FormularioContacto/index.php';
        include __DIR__ . '/../views/Layouts/footer.php';
    }


    public function sendMessage($nombre, $correo, $mensaje) {
        if (!empty($nombre) && !empty($correo) && !empty($mensaje)) {
            return "Mensaje enviado correctamente!";
        } else {
            return "Error: Por favor, complete todos los campos.";
        }
    }
}
?>
