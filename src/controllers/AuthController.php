<?php
class AuthController {
    public function login() {
        // Carga la vista del login
        include __DIR__ . '/../views/login.php';
    }

    public function index() {
        // Método por defecto si no se pasa método
        $this->login();
    }

    public function validar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        require_once __DIR__ . '/../models/Usuario.php';
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->validarUsuario($correo, $contrasena);

        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario['NOMBRE_USUARIO'];
            $_SESSION['id_usuario'] = $usuario['ID_USUARIO'];
            // Redirección dinámica mañana le pregunto a miguel
        switch ($_SESSION['rol']) {
            case 'admin':
                header('Location: index.php?url=admin/agregar');
                break;
            case 'coordinador':
                header('Location: index.php?url=coordinador/dashboard');
                break;
            case 'empresa':
                header('Location: index.php?url=empresa/perfil');
                break;
            default:
                header('Location: index.php?url=main/home');

            exit;
        } else {
            echo "<p style='color:red;'>Credenciales incorrectas</p>";
            include __DIR__ . '/../views/login.php';
        }
    }
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
        include __DIR__ . '/../views/main.php'; // Asegúrate que esta vista exista
    }
}
