<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../database/conectar.php';
// a lo que entendi aqui se controla metodos que tiene que ver con el usuario como listarlos obtenerlos
//o eliminar y estos son los metodos
class UsersController {

    private  $conn;

    public function __construct() {
        $conexion = new Conectar();
        $this->conn=$conexion->conectar();
    }

    // 🔹 Obtener todos los usuarios
    public function obtenerUsuarios() {
        $sql = "SELECT * FROM usuario";
        $stmt = oci_parse($this->conn, $sql);
        oci_execute($stmt);

        $usuarios = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }
    public function mostrarUsuarios ($usuarios[]){
        
    }

    // 🔹 Obtener un usuario por ID
    public function obtenerUsuarioPorId($id_usuario) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ":id", $id_usuario);
        oci_execute($stmt);
        return oci_fetch_assoc($stmt);
    }

    
}
?>
