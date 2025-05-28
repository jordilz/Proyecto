<?php
// Autocargar los controladores si estás usando múltiples archivos
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/app/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Obtener la ruta desde la URL (por ejemplo: ?url=home/index)
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Asignar controlador, método y parámetros
$controllerName = ucfirst($url[0]) . 'Controller';
$method = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// Verificar que el controlador y el método existen
if (file_exists(__DIR__ . '/app/controllers/' . $controllerName . '.php')) {
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        echo "Método '$method' no encontrado en el controlador '$controllerName'.";
    }
} else {
    echo "Controlador '$controllerName' no encontrado.";
}