<?php
class HomeController {
    public function index() {
        // Use __DIR__ to get the current directory of this file
        include __DIR__ . '/../views/header.php';
        include __DIR__ . '/../views/index.php';
        include __DIR__ . '/../views/footer.php';
    }
}
?>