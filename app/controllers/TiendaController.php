<?php
class TiendaController {
    public function index() {
        include __DIR__ . '/../views/Layouts/header.php';
        include __DIR__ . '/../views/Tienda/index.php';
        include __DIR__ . '/../views/Layouts/footer.php';
    }

    public function cesta() {
    require_once __DIR__ . '/../views/Layouts/header.php';
    require_once __DIR__ . '/../views/Tienda/cesta.php';
    require_once __DIR__ . '/../views/Layouts/footer.php';
}

}
?>

