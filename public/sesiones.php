<?php
require_once DATABASE_PATH;

class Sesion {

    // Iniciar sesión PHP
    public static function start(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Registrar datos del usuario en sesión
    public static function login($user){
        $_SESSION['user'] = [
            'id'   => $user['ID_USUARIO'],
            'name' => $user['CORREO_USUARIO'],
            'rol'  => $user['ID_TIPO_USUARIO']
        ];
    }

    // ¿Hay sesión PHP?
    public static function isLogged(){
        return isset($_SESSION['user']['id']);
    }

    // Obtener usuario
    public static function user(){
        return $_SESSION['user'] ?? null;
    }

    // Obtener rol
    public static function role(){
        return $_SESSION['user']['rol'] ?? null;
    }

    // 🔐 Verificar sesión ACTIVA en BD
    public static function tieneSesionActiva($conn){
        self::start();

        if (!isset($_SESSION['user']['id'])) {
            return false;
        }

        if (!$conn) {
            return false;
        }

        $idUsuario = $_SESSION['user']['id'];

        $sql = "SELECT 1
                FROM sesion
                WHERE id_usuario = :id
                  AND fecha_fin IS NULL
                  AND valido = 1";

        $stm = oci_parse($conn, $sql);
        oci_bind_by_name($stm, ':id', $idUsuario);
        oci_execute($stm);

        return oci_fetch_assoc($stm) !== false;
    }

    // 🚪 Middleware de protección
    public static function requireLogin(){
    self::start();

    $conexion = new Conexion();
    $conn = $conexion->conectar();

    if (!self::tieneSesionActiva($conn)) {
        header("Location: index.php?url=auth/login");
        exit;
    }
}


    // Roles
    public static function requireRole($conn, $roles = []){
        self::requireLogin($conn);

        if (!in_array(self::role(), $roles)) {
            header("Location: index.php?url=auth/login");
            exit;
        }
    }

    // Logout PHP
    public static function logout(){
        session_unset();
        session_destroy();
    }
}
