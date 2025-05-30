<?php
class HomeController {
    public function index() {
        include __DIR__ . '/../views/Layouts/header.php';
        include __DIR__ . '/../views/Home/index.php';
        include __DIR__ . '/../views/Layouts/footer.php';
    }
}
?>