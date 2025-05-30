<?php
class LoMasVistoController {
    public function index() {
        include __DIR__ . '/../views/Layouts/header.php';
        include __DIR__ . '/../views/LoMasVisto/index.php';
        include __DIR__ . '/../views/Layouts/footer.php';
    }
}
?>