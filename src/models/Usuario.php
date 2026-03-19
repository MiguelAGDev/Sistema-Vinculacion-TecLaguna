<?php 
/** Esta clase requiere */
require_once DATABASE_PATH;
require_once MODELS_PATH . 'Permisos.php';
    class usuario {
        /** 
         * Summary of conn
         * @var Conexion $conn 
         * se declara la clase 'conn' que sera un objeto conexion,
         * para conectar y desconectar
         */
        private $conn;
        
        
        /* Summary of __construct
        * Metodo que construye la conexion, simplemente la inicializa
        * dentro del objeto "conn"
        */ 
        public function __construct(){
            $conn= new Conexion();
            $this->conn =$conn->conectar();
        }
        
        /**
         * Summary of validarUsuario
         * @param varchar2 $correo Correo electronico del usuario
         * @param HashContext $contrasena COntrasena ingresada para validar 
         * @return void
         */
public function validarUsuario($correo, $contrasena) {
    $sql = "SELECT * FROM usuario WHERE correo_usuario = :correo";
    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ':correo', $correo);

    $valido = 0;

    if (oci_execute($stmt)) {
        $row = oci_fetch_assoc($stmt);

        if ($row) {
            $permisos = 1;

            if (Permisos::verificarPermiso($permisos, Permisos::PERMISO_INICIAR_SESION)) {
                $hashGuardado = $row['CONTRASENA_USUARIO'];

                if (password_verify($contrasena, $hashGuardado)) {
                    $valido = 1;

                    // Registrar sesión
                    $sql2 = "INSERT INTO sesion (id_usuario, fecha_inicio, fecha_fin, valido)
                             VALUES (:id_usuario, SYSDATE, NULL, :valido)";
                    $stmt2 = oci_parse($this->conn, $sql2);
                    oci_bind_by_name($stmt2, ':id_usuario', $row['ID_USUARIO']);
                    oci_bind_by_name($stmt2, ':valido', $valido);
                    oci_execute($stmt2);

                    return $row;
                } else {
                    echo "<p style='color:red'>Contraseña incorrecta</p>";
                }
            } else {
                echo "<p style='color:red'>No tiene permiso de iniciar sesión</p>";
            }
        } else {
            echo "<p style='color:red'>Usuario no encontrado</p>";
        }
    } else {
        echo "<p style='color:red'>Error al ejecutar la consulta</p>";
    }

    return false;
}
    }