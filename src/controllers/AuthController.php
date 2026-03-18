<?php
 require_once __DIR__ .'/../../public/sesiones.php';
 require_once __DIR__ .'/../../database/Conexion.php';
 require_once __DIR__.'/../models/Permisos.php';
class AuthController {
/***************************************FUNCIONES PARA LAS VISTAS******************************************** 
*************** nada mas no sirven para movernos por medio del index hay una para cada vista ****************/

    public function index() {
        $this->login();
    }

    public function login() {
        include __DIR__ . '/../views/loginView.php';
    }
     public function confirmacion() {
        include __DIR__ . '/../views/confirmacion.php';
    }
    public function agregarUsuario() {
        include __DIR__ . '/../views/agregarUsuarioView.php';
    }
    public function panelAdministracion (){
        include __DIR__.'/../views/panelAdministracionView.php';
    }
      public function usuarios() {
        require_once __DIR__ . '/../views/usuariosView.php';
    }
    public function main() {
        require_once __DIR__ . '/../views/mainView.php';
    }
    public function adminFlyersManageView() {
        require_once __DIR__ . '/../views/adminFlyersManageView.php';
    }
     public function perfil() {
        require_once __DIR__ . '/../views/perfilView.php';
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
        if($resultado){
            header("Location: index.php?url=auth/panelAdministracion");
            exit;

        }else{
            header('Location: index.php?url=auth/login');
            exit;
        }
    }
    public function validacionC($correo, $contrasena) {
   if(Sesion::tieneSesionActiva($this->con)){
    return [
        'sesion_activa'=> true,
        'usuario'=>Sesion::user()
    ];
   }
    $sql = "SELECT * FROM usuario WHERE correo_usuario = :correo";
    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':correo', $correo);

    public function home() {
        $this->verificarSesion();
        include __DIR__ . '/../views/mainView.php';
    }
        $row = oci_fetch_assoc($stmt);

        if (!$row) {
             
            return false;
        }
            $permisos = 1 ;

            if (!Permisos::verificarPermiso($permisos, Permisos::PERMISO_INICIAR_SESION)) {
                 
                return false;
            }
                $hashGuardado = $row['CONTRASENA_USUARIO'];

                if (!password_verify($contrasena, $hashGuardado)) {
                   
                    return false;
                }
                    $valido = 1;

                    // Registrar sesión
                    $sql2 = "INSERT INTO sesion (id_usuario, fecha_inicio, fecha_fin, valido)
                             VALUES (:id_usuario, SYSDATE, NULL, 1)";
                    $stmt2 = oci_parse($this->conn, $sql2);
                    oci_bind_by_name($stmt2, ':id_usuario', $row['ID_USUARIO']);
                    oci_execute($stmt2,OCI_COMMIT_ON_SUCCESS);
                    Sesion::start();
                    Sesion::login($row);

                    return true;
}
    public static function requireLogin(){
        $conn = null;
        Sesion::requireLogin($conn);
    }
   public function logout()
{
    Sesion::start();

    $usuario = Sesion::user();

    if ($usuario) {
        $sql = "UPDATE sesion
                SET fecha_fin = SYSDATE,
                    valido = 0
                WHERE id_usuario = :id
                  AND fecha_fin IS NULL";

        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':id', $usuario['id']);
        oci_execute($stmt);
    }

    
    Sesion::logout();

    header('Location: index.php?url=auth/login');
    exit;
}

    public function role(){
        return Sesion::role();
    }
}
     
    

