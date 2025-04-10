<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../controllers/HomeController.php';

class HomeControllerTest extends TestCase
{
    public function testIndexDoesNotThrowError()
    {
        $controller = new HomeController();
        
        ob_start(); // Evita que se impriman las vistas
        $controller->index();
        ob_end_clean();

        $this->assertTrue(true); // Si llega aquí, no hubo errores
    }
}
