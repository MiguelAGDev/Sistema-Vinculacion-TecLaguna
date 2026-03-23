<?php

 require_once PUBLIC_PATH . 'sesiones.php';
 require_once DATABASE_PATH;
 require_once MODELS_PATH . 'Permisos.php';
class AuthController {
/***************************************FUNCIONES PARA LAS VISTAS******************************************** 
 * nada mas no sirven para movernos por medio del index hay una para cada vista 
*/
    private $sesion;
    private $conn = null;

    public function  __construct(){
         $conn= new Conexion();
         $this->conn = $conn->conectar();
    }
    
    public function index() {
        $this->login();
    }

    public function login() {
        include VIEWS_PATH . 'login.php';
    }
     public function confirmacion() {
        include VIEWS_PATH . 'confirmacion.php';
    }
    public function agregarUsuario() {
        include VIEWS_PATH . 'agregarUsuario.php';
    }
    public function panelAdministracion (){
        include VIEWS_PATH . 'panelAdministracion.php';
    }
      public function usuarios() {
        require_once VIEWS_PATH . 'usuarios.php';
    }
    public function main() {
        require_once VIEWS_PATH . 'main.php';
    }
    public function adminFlyersManageView() {
        require_once VIEWS_PATH . 'adminFlyersManageView.php';
    }
     public function perfil() {
        require_once VIEWS_PATH . 'perfil.php';
    }
    public function flyerCreateView() {
        require_once VIEWS_PATH . 'flyerCreateView.php';
    }
     public function registro() {
        require_once __DIR__ . '/../views/agregarUsuario.php';
    }
    /********************************************************************************************************* */
    /*****************************VALIDACION*************************************************************** 
     * Basiacemte es para entrar a la pagina existe un metodo en usuarios y lo que hace que que verifica si esta el correo
     * tambien la contraseña  hasheada y despues en est emetodo inicia la sesion y nos redirecciona por medio al 
     * panel de control  ahi se valida que tipo de usuario es y muestra las vistas a las que puede ingresar
    */
    public function validar() {
    ob_start();
     $resultado = true;
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $correo = trim($_POST['correo'] ?? '');
            $contrasena = $_POST ['contrasena'] ?? '';
            $resultado = $this->validacionC($correo,$contrasena);
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
   if(Sesion::tieneSesionActiva($this->conn)){
    return [
        'sesion_activa'=> true,
        'usuario'=>Sesion::user()
    ];
   }
    $sql = "SELECT * FROM usuario WHERE correo_usuario = :correo";
    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':correo', $correo);

    if (!oci_execute($stmt)) {
         
        return false;
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
     
    

