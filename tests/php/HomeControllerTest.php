<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/controllers/HomeController.php';

class HomeControllerTest extends TestCase
{
    public function testIndexDoesNotThrowError()
    {
        // Creamos una instancia de HomeController
        $controller = new HomeController();
        
        // Usamos output buffering para evitar que la salida se imprima
        ob_start();
        
        // Llamamos al método index
        $controller->index();
        
        // Capturamos la salida generada por las vistas
        $output = ob_get_clean();

        // Verificamos que la salida no esté vacía (es decir, que las vistas hayan sido cargadas)
        $this->assertNotEmpty($output, "Las vistas no se cargaron correctamente.");
    }

    public function testIndexIncludesHeaderView()
    {
        // Creamos una instancia de HomeController
        $controller = new HomeController();

        // Usamos output buffering
        ob_start();
        
        // Llamamos al método index
        $controller->index();

        // Capturamos la salida generada por las vistas
        $output = ob_get_clean();

        // Verificamos que la vista header.php fue incluida
        $this->assertStringContainsString('header', $output, "La vista 'header.php' no fue cargada.");
    }

    public function testIndexIncludesFooterView()
    {
        // Creamos una instancia de HomeController
        $controller = new HomeController();

        // Usamos output buffering
        ob_start();
        
        // Llamamos al método index
        $controller->index();

        // Capturamos la salida generada por las vistas
        $output = ob_get_clean();

        // Verificamos que la vista footer.php fue incluida
        $this->assertStringContainsString('footer', $output, "La vista 'footer.php' no fue cargada.");
    }
}
?>
