<?php

// Este codigo cargara la carpeta
require_once __DIR__.'\..\config\config.php';
echo "<h3> Prueba de configuracion</h3>";


// Probamos las constantes principales
echo "<strong>ROOT_PATH:</strong> " . ROOT_PATH . "<br>";
echo "<strong>BASE_URL:</strong> " . BASE_URL . "<br>";
echo "<strong>ASSETS_PATH:</strong> " . ASSETS_PATH . "<br>";
echo "<strong>ASSETS_URL:</strong> " . ASSETS_URL . "<br>";

echo "<hr>";

// Verificar que las sesiones funcionan
$_SESSION['test'] = 'Sesión funcionando correctamente';
echo "<strong>SESSION:</strong> " . $_SESSION['test'] . "<br>";

// Verificamos la zona horaria y fecha actual
echo "<strong>Zona horaria actual:</strong> " . date_default_timezone_get() . "<br>";
echo "<strong>Fecha actual:</strong> " . date('Y-m-d H:i:s');