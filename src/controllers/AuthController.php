<?php
class AuthController {
    public function login() {
        // Aquí cargas la vista del login (en tu carpeta views)
        require_once __DIR__ . '/../views/Login.php';
    }

    public function index() {
        // Método por defecto si no se pasa método, puede redirigir a login
        $this->login();
    }
}
