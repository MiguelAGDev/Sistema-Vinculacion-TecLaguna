<?php

//require __DIR__.'/../vendor/autoload.php';

/* 16 de Noviembre 2025 01:36 AM */
/** Sumary of Index.php
 * El index recibe las direcciones url para cada una de las 
 * acciones que se realiza en el sistemas, es decir, se llama 
 * al index con href = index.php?*url*. 
 * 
 * Si no se tiene direccion y solo se llama a index.php, este 
 * la ur se inicializa como url = auth/login lo que envia al 
 * inicio de sesion es decir, pasa esto
 * httppd
 * 
 */

/* Cargamos las configuraciones generales del config */ 
require_once __DIR__.'/../config/Config.php';

/** @var string $url Obtiene el valor de la ruta URL que se presenta al 
 * realizar un accion dentro del sistema y lo redirige a la ventana requerida. 
 * Por defecto se inicializa con 'auth/login'
 * 
 * El valor se obtiene con el href que se llame. Un ejemplo, es que tenemos un 
 * boron cerrar session y ese a su vez tendra el url 
 * <a href=index.php?url=auth/logout>cerrar sesion</a> en donde se le asigna 
 * url = 'auth/logout' y con el metodo GET obtenemos ese valor aqui dentro
 */
$url = $_GET['url'] ?? 'auth/login';

/** @var array $urlParts Sepra el url en un arreglo, el valor por defecto
 * auth/login se transforma en $ulrParts = ['auth', 'login']; 
 */
$urlParts = explode('/', $url);

/** @var string $controllerName Define el nombre del controlador tomando 
 * la poscicion[0], despues la primera letra en mayuscula y concatena 
 * poniendo el sufijo controller. 
 * 
 * 
 * Ej
 * urlPats = ['auth', 'login'] 
 * controlName = ucfirst($urlParts[0]) . 'Controller' -> ucfirst('auth') . 'Controller' 
 * -> 'Auth' . 'Controller' -> 'AuthController'
 *  
 */
$controllerName = ucfirst($urlParts[0]) . 'Controller';

/**
 * @var string $method Toma el valor de la posicion 2 del $urlParts, sino existe,
 * toma el valor de 'login'.
 * 
 * Ej. $urlParts = ['auth','login']
 * $method = ['login'] 
 */
$method = $urlParts[1] ?? 'index';

/** @var PATH $controllerFile concatena $controllerName, para redigirnos directamente al archivo
 * Ej.   
 * $controllerName = 'AuthController'
 * $controllerFile = __DIR__ . '/../src/controllers/' . $controllerName . '.php'
 * $controllerFile = __DIR__ . '/../src/controllers/' . 'AuthController' . '.php'
 */
$controllerFile = __DIR__ . '/../src/controllers/' . $controllerName . '.php';

/**Verificar si existe el archivo del controlador */ 
if (file_exists($controllerFile)) {
    
    /** Llama a la clase controllador, en el default llama a AuthController.php
     * Ej 
     * $controllerFile = __DIR__.'/../src/controllers/AuthController.php'
     * require_once = __DIR__.'/../src/controllers/AuthController.php' 
     */
    require_once $controllerFile;
    
    /** Verificar si la clase existe con el mismo nombre dle controllador
     *  XX ADVERTENCIA XX
     * Si la clase se llama Clase.php, la clase tiene que llevar exacatamente 
     * el mismo nombre 'Clase', con cada uno de los caracteres identicos
    */
    if (class_exists($controllerName)) {
        
        /**
         * @var controllerName $controller Es un objeto controller, que depende de que file se haya 
         * recibido, obtiene el valor de AuthController, AdminController o UserController.
         * 
         * Ej. $controllName = 'AuthController'
         *     $controller = new AuthController();
         */
        $controller = new $controllerName();
        

        /** Se verifica si el metodo existe dentro del objeto $controller
         *  
         * Summary of method_exist
         * @param object_or_class $controller Objeto que se inicializa antes de esta funcion
         * @param method $method Nombre del methodo que se quiere llamar
         * @return bool Retorna un false o true si existe
         */
        if (method_exists($controller, $method)) {
            /** Si esque exsite, se ejecuta el metodo
             * Ej. $controller = new AuthController();
             *     $method = login
             * 
             *      $controller->login();
             */
            $controller->$method();
        } else {
            /**Sino exite dicho metodo, se manda el error */
            echo "Error: Método '$method' no existe en $controllerName.";
        }
    } else {
        /**Si no existe la clase se retorna el error */
        echo "Error: Clase $controllerName no encontrada.";
    }
} else {
    /**Si no existe el controlador se retorna el error */
    echo "Error: Controlador no encontrado.";
}