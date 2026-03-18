<?php
class AuthController {
/***************************************FUNCIONES PARA LAS VISTAS******************************************** 
*************** nada mas no sirven para movernos por medio del index hay una para cada vista ****************/

    public function index() {
        $this->login();
    }

    public function login() {
        include __DIR__ . '/../views/loginView.php';
    }

    public function agregarUsuario() {
        include __DIR__ . '/../views/agregarUsuarioView.php';
    }
    public function panelAdministracion (){
        include __DIR__.'/../views/panelAdministracionView.php';
    }
      public function usuarios() {
        require_once __DIR__ . '/../views/usuarios.php';
    }
    public function main() {
        require_once __DIR__ . '/../views/mainView.php';
    }
    public function adminFlyersManageView() {
        require_once __DIR__ . '/../views/adminFlyersManageView.php';
    }
     public function perfil() {
        require_once __DIR__ . '/../views/perfil.php';
    }
    public function flyerCreateView() {
        require_once __DIR__ . '/../views/flyerCreateView.php';
    }
    /********************************************************************************************************* */

    
    /*****************************VALIDACION*************************************************************** 
     * Basiacemte es para entrar a la pagina existe un metodo en usuarios y lo que hace que que verifica si esta el correo
     * tambien la contraseña  hasheada y despues en est emetodo inicia la sesion y nos redirecciona por medio al 
     * panel de control  ahi se valida que tipo de usuario es y muestra las vistas a las que puede ingresar
    */
    public function validar() {
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = $_POST['correo'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        require_once __DIR__ . '/../models/Usuario.php';
        $this->usuarioModel = new Usuario(); // Asignación correcta si estás en una clase
        $usuario = $this->usuarioModel->validarUsuario($correo, $contrasena);
        if ($usuario) {
            //inica sesion
            session_start();
            //se pasan esos datatos por medio de otro metodo esos datos sirven para validad
            $_SESSION['usuario'] = $usuario['NOMBRE_USUARIO'];
            $_SESSION['id_usuario'] = $usuario['ID_USUARIO'];
            $_SESSION['id_tipo_usuario'] = $usuario['ID_TIPO_USUARIO'];
            header('Location: index.php?url=auth/panelAdministracion');
            exit;
        } else {
            echo "<p style='color:red;'>Credenciales incorrectas</p>";
            include __DIR__ . '/../views/loginView.php';
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
        include __DIR__ . '/../views/mainView.php';
    }
}

