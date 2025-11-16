<?php

/* 16 de Noviembre 2025 01:36 AM */

/* Cargamos las configuraciones generales del config */ 
require_once __DIR__.'/../config/Config.php';

// Obtener la ruta desde la URL, ejemplo: ?url=auth/login
// Cambié el valor por defecto a 'auth/login' para que la página inicial sea el login
$url = $_GET['url'] ?? 'auth/login';
// Si existe algo en url  = GET['url'] que puede ser algo como url = admin/AgregarUsuario
// SI ES NULO url = ath/login;

// Separar la ruta en partes por '/'
$urlParts = explode('/', $url);

// Definir nombre del controlador
// Primera parte de la URL, primera letra mayúscula + "Controller"
$controllerName = ucfirst($urlParts[0]) . 'Controller';

// Definir método a llamar (segunda parte de la URL) o "index" por defecto
$method = $urlParts[1] ?? 'index';

// Ruta completa al archivo del controlador
$controllerFile = __DIR__ . '/../src/controllers/' . $controllerName . '.php';

// Verificar si existe el archivo del controlador
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Verificar si la clase existe
    if (class_exists($controllerName)) {
        // Instanciar el controlador
        $controller = new $controllerName();
        
        // Verificar si el método existe dentro del controlador
        if (method_exists($controller, $method)) {
            // Ejecutar el método
            $controller->$method();
        } else {
            echo "Error: Método '$method' no existe en $controllerName.";
        }
    } else {
        echo "Error: Clase $controllerName no encontrada.";
    }
} else {
    echo "Error: Controlador no encontrado.";
}