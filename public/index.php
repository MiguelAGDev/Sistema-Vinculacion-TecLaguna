<?php
// Obtenemos la ruta de la URL (esto depende de tu .htaccess)
$ruta = $_GET['ruta'] ?? 'login';

// Enrutador simple
switch ($ruta) {
    case 'login':
        require '../src/controllers/LoginController.php';
        break;
        
    case 'flyer':
        require '../src/controllers/FlyerController.php';
        break;
        
    case 'main':
        require '../src/controllers/MainController.php';
        break;

    default:
        // Página de error 404
        require '../src/views/404.php';
        break;
}