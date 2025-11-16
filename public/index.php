<?php
// public/index.php (Punto de entrada con enrutador)

// Configuración básica
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

// Obtener la ruta solicitada
$ruta = $_GET['ruta'] ?? 'main';

// Sistema de enrutamiento
switch ($ruta) {
    case 'login':
        require_once '../src/controllers/LoginController.php';
        $controller = new LoginController();
        $controller->index();
        break;
        
    case 'flyer':
        // Lista de flyers (futuro)
        require_once '../src/controllers/FlyerViewController.php';
        $controller = new FlyerViewController();
        $controller->index();
        break;
        
    case 'flyer/show':
        // Ver detalle de un flyer
        require_once '../src/controllers/FlyerViewController.php';
        $controller = new FlyerViewController();
        $controller->show();
        break;
    
    case 'flyer/create':
        // Formulario de creación
        require_once '../src/controllers/FlyerCreateController.php';
        $controller = new FlyerCreateController();
        $controller->create();
        break;
        
    case 'flyer/store':
        // Guardar flyer
        require_once '../src/controllers/FlyerCreateController.php';
        $controller = new FlyerCreateController();
        $controller->store();
        break;
        
    case '43/upload_img':
        require_once '../src/controllers/services/imgController.php';
        $controller = new imgController();
        $controller->uploadImage();
        break;
    case 'main':
    case '':
        require_once '../src/controllers/MainController.php';
        $controller = new MainController();
        $controller->index();
        break;

    default:
        // Página de error 404
        http_response_code(404);
        require_once '../src/views/404.php';
        break;
}
?>