<?php
class LoMasVistoController {
    public function index() {
        // Use __DIR__ to get the current directory of this file
        include __DIR__ . '/../views/Layouts/header.php';
        include __DIR__ . '/../views/LoMasVisto/index.php';
        include __DIR__ . '/../views/Layouts/footer.php';
    }
}
?>