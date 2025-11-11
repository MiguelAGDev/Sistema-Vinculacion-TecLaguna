<?php
require_once __DIR__. '/../../database/conectar.php';
    
    class administrador{
        private $conn;

        public function __construct(){
            $c= new Conectar();
            $this->conn =$c->metodoConectar();

        }
        public function obtenerUsuarios (){
            $sql ="SELECT id_usuario, nombre_usuario, correo_usuario, telefono_usuario 
                   FROM usuario";
            $stmt = oci_parse ($this->conn,$sql);
            oci_execute($stmt);
            return $stmt;       
        }
       public function crearUsuario($nombre, $correo, $telefono, $contrasena, $nombre_tipo, $nombre_carrera, $extra = []) {
 
    switch ($nombre_tipo) {
        case "Alumno": $nombre_tipo = 1; break;
        case "Residente": $nombre_tipo = 2; break;
        case "Egresado": $nombre_tipo = 3; break;
        default: $nombre_tipo = null;
    }

   
    $id_carrera = $nombre_carrera ?? null;

    $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

    
    $sql = "INSERT INTO usuario (
                nombre_usuario,
                correo_usuario,
                telefono_usuario,
                contrasena_usuario,
                activo_usuario,
                id_tipo_usuario,
                id_carrera
            ) VALUES (
                :nombre,
                :correo,
                :telefono,
                :contrasena,
                1,
                :id_tipo,
                :id_carrera
            )
            RETURNING id_usuario INTO :id_usuario";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":correo", $correo);
    oci_bind_by_name($stmt, ":telefono", $telefono);
    oci_bind_by_name($stmt, ":contrasena", $contrasena);
    oci_bind_by_name($stmt, ":id_tipo", $nombre_tipo);
    oci_bind_by_name($stmt, ":id_carrera", $id_carrera);
    oci_bind_by_name($stmt, ":id_usuario", $id_usuario, 32);

    // 🔹 5. Ejecutar primer insert
    if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
        $e = oci_error($stmt);
        echo "<pre style='color:red;'> Error al insertar en usuario:\n" . print_r($e, true) . "</pre>";
        oci_rollback($this->conn);
        return false;
    }else{
         echo "<p style='color:green;'> Usuario insertado con ID: $id_usuario</p>";
    }


    // 🔹 6. Insertar datos específicos según el tipo
    switch ($nombre_tipo) {
        case 1: // Alumno
            $sql2 = "INSERT INTO alumno (id_usuario, matricula_alumno, semestre_alumno)
                     VALUES (:id_usuario, :matricula_alumno, :semestre_alumno)";
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":matricula_alumno", $extra['matricula']);
            oci_bind_by_name($stmt2, ":semestre_alumno", $extra['semestre']);
            break;

        case 2: // Residente
            $sql2 = "INSERT INTO residente (id_usuario, proyecto_residente, id_asesor, id_empresa)
                     VALUES (:id_usuario, :proyecto_residente, :id_asesor, :id_empresa)";
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":proyecto", $extra['proyecto']);
            oci_bind_by_name($stmt2, ":asesor", $extra['asesor']);
            oci_bind_by_name($stmt2, ":empresa", $extra['empresa']);
            break;

        case 3: // Egresado
            $sql2 = "INSERT INTO egresado (id_usuario, anio_egreso, empleo_actual)
                     VALUES (:id_usuario, :anio_egreso, :empleo_actual)";
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":anio_egreso", $extra['anio_egreso']);
            oci_bind_by_name($stmt2, ":empleo_actual", $extra['empleo']);
            break;

        default:
            $stmt2 = null;
    }

    if ($stmt2 && !oci_execute($stmt2, OCI_NO_AUTO_COMMIT)) {
        $e = oci_error($stmt2);
        echo "<pre style='color:red;'>Error al insertar datos del tipo de usuario:\n" . print_r($e, true) . "</pre>";
        oci_rollback($this->conn);
        return false;
    }

    oci_commit($this->conn);

    echo "<p style='color:green;'> Commit realizado correctamente.</p>";
    return $id_usuario;
}

    public function actualizarUsuario($id, $nombre, $correo, $telefono) {
        $sql = "UPDATE usuario
                SET nombre_usuario = :nombre,
                    correo_usuario = :correo,
                    telefono_usuario = :telefono
                WHERE id_usuario = :id
                commit;";
        $stmt = oci_parse($this->conn, $sql);

        oci_bind_by_name($stmt, ":nombre", $nombre);
        oci_bind_by_name($stmt, ":correo", $correo);
        oci_bind_by_name($stmt, ":telefono", $telefono);
        oci_bind_by_name($stmt, ":id", $id);

        return oci_execute($stmt);
    }
    }

?>