<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/PermisosController.php';

// No permitir acceso sin sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?url=auth/login");
    exit;
}

$nombreUsuario = $_SESSION['usuario'];
$permisosUsuario = $_SESSION['permisos_usuario'] ?? 0;

// Cargar HTML del menú
include __DIR__ . '/menu.html';
