<?php
class AuthController {

    public function index() {
        $this->login();
    }

    public function login() {
        include __DIR__ . '/../views/login.php';
    }

    public function agregarUsuario() {
        include __DIR__ . '/../views/agregarUsuario.php';
    }
    public function panelAdministracion (){
        include __DIR__.'/../views/panelAdministracion.php';
    }

    public function validar() {
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        require_once __DIR__ . '/../models/Usuario.php';
        $this->usuarioModel = new Usuario(); // Asignación correcta si estás en una clase
        $usuario = $this->usuarioModel->validarUsuario($correo, $contrasena);
        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario['NOMBRE_USUARIO'];
            $_SESSION['id_usuario'] = $usuario['ID_USUARIO'];
            $_SESSION['id_tipo_usuario'] = $usuario['ID_TIPO_USUARIO'];
            header('Location: index.php?url=auth/panelAdministracion');
            exit;
        } else {
            echo "<p style='color:red;'>Credenciales incorrectas</p>";
            include __DIR__ . '/../views/login.php';
        }
    }

    ob_end_flush();
}


    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?url=auth/login');
        exit;
    }

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }

    public function home() {
        $this->verificarSesion();
        include __DIR__ . '/../views/main.php';
    }
}

