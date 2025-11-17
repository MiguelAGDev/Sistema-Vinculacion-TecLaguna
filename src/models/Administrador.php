<?php
require_once __DIR__. '/../../database/conectar.php';
require_once __DIR__. '/../../database/Conexion.php';
    
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

      public function insertarUsuario($nombre,$correo,$telefono,$contrasena,$tipo,$carrera,$datosExtra){
        $sql = "INSERT INTO usuario (nombre_usuario,correo_usuario,telefono_usuario,contrasena_usuario,activo_usuario,id_tipo_usuario,id_carrera) 
                VALUES (:nombre,:correo,:telefono,:contrasena,1,:id_tipo,:id_carrera)
                RETURNING id_usuario INTO :id_usuario";

        $hashed = password_hash($contrasena,PASSWORD_DEFAULT);

        $stmt = oci_parse($this->conn,$sql);
        oci_bind_by_name($stmt,":nombre",$nombre);
        oci_bind_by_name ($stmt,":correo",$correo);
        oci_bind_by_name ($stmt,":telefono",$telefono);
        oci_bind_by_name($stmt, ":contrasena", $hashed);
        oci_bind_by_name($stmt, ":id_tipo", $tipo);
        oci_bind_by_name($stmt, ":id_carrera", $carrera);

        oci_bind_by_name($stmt, ":id_usuario", $id_usuario, 32);

        if(oci_execute($stmt,OCI_COMMIT_ON_SUCCESS)){
              echo "<p style='color:green;'> Usuario insertado </p>";
        }else{
             echo "<p style='color:red;'> Usuario no insertado </p>";
        }

        switch ($tipo){
            case '1':
               $sql2 = "INSERT INTO alumno (id_usuario, matricula_alumno, semestre_alumno)
                     VALUES (:id_usuario, :matricula_alumno,:semestre_alumno)";
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":matricula_alumno", $datosExtra['matricula']);
            oci_bind_by_name($stmt2, ":semestre_alumno", $datosExtra['semestre']);
            break;

            case 2: // Residente
            $sql2 = "INSERT INTO residente (id_usuario, proyecto_residente, id_asesor, id_empresa)
                     VALUES (:id_usuario,EMPTY_BLOB(),:id_asesor,:id_empresa)
                     RETURNING proyecto_residente INTO :proyecto_residente";
            $stmt2 = oci_parse($this->conn, $sql2);
            $lob = oci_new_descriptor($this->conn, OCI_D_LOB);
            
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":proyecto_residente", $lob, -1, OCI_B_BLOB);
            oci_bind_by_name($stmt2, ":id_asesor", $datosExtra['asesor']);
            oci_bind_by_name($stmt2, ":id_empresa", $datosExtra['empresa']);
            break;

            case 3: // Egresado
            $sql2 = "INSERT INTO egresado (id_usuario, anio_egreso, empleo_actual)
                     VALUES (:id_usuario, :anio_egreso, :empleo_actual)";
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario);
            oci_bind_by_name($stmt2, ":anio_egreso", $datosExtra['anio_egreso']);
            oci_bind_by_name($stmt2, ":empleo_actual", $datosExtra['empleo']);
            break;
            
            case 4: // Empresa
            $sql2 = "INSERT INTO empresa ( nombre_empresa,correo_empresa,telefono_empresa,id_tipo_usuario,id_usuario)
                     VALUES (:nombre,:correo,:telefono,:id_tipo,:id_usuario)";
                     
            $stmt2 = oci_parse($this->conn, $sql2);
            oci_bind_by_name($stmt2, ":nombre", $nombre);
            oci_bind_by_name($stmt2, ":correo", $correo);
            oci_bind_by_name($stmt2, ":telefono", $telefono);
            oci_bind_by_name($stmt2, ":id_tipo", $tipo);
            oci_bind_by_name($stmt2, ":id_usuario", $id_usuario, 32);
            break;
        }
            if ($tipo === '2') {
                if (oci_execute($stmt2, OCI_DEFAULT)) {
                    if ($lob->save($datosExtra['proyecto'])) {
                    oci_commit($this->conn);
                    echo "<p style='color:green;'> Usuario y archivo insertados correctamente </p>";
                    } else {
                    oci_rollback($this->conn);
                    echo "<p style='color:red;'> Error al guardar el contenido del archivo </p>";
                        }
                } else {
                echo "<p style='color:red;'> Error al insertar usuario residente </p>";
                }
            $lob->free();
            oci_free_statement($stmt2);
           } else {

            if (oci_execute($stmt2, OCI_COMMIT_ON_SUCCESS)) {
                echo "<p style='color:green;'> Usuario insertado </p>";
            } else {
                echo "<p style='color:red;'> Usuario no insertado </p>";
            }
        }
    }

     public function buscarUsuarioPorId($id_usuario) {
        $sql = "SELECT  ID_USUARIO,
                    NOMBRE_USUARIO,
                    CORREO_USUARIO,
                    TELEFONO_USUARIO,
                    ACTIVO_USUARIO,
                    NOMBRE_CARRERA
                FROM usuario u
                LEFT JOIN CARRERA c ON c.ID_CARRERA = u.ID_CARRERA
                WHERE u.ID_USUARIO = :id";

        $stid = oci_parse($this->conn, $sql);

        oci_bind_by_name($stid, ":id", $id_usuario);

        oci_execute($stid);

        $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);
        if($row['ACTIVO_USUARIO'] === '1'){
            $row['ACTIVO_USUARIO']='ACTIVO';
        }else{
            $row['ACTIVO_USUARIO']='NO ESTA ACTIVO POR EL MOMENTO';
        }
        return $row ?: false;
    }
    
    public function actUsuarios ($nombre,$correo,$telefono,$activo,$carrera,$ext){
        $sql ="UPDATE usuarios
               SET 
                    nombre_usuario = :nombre,
                    correo_usuario = :correo,
                    telefono_usuario = :telefono,
                    activo_usuario = :activo
                WHERE id_usuario = :id";
        $stmt=oci_parse($this->conn,$sql);
        oci_bind_by_name($stmt,':nombre',$nombre);
        oci_bind_by_name ($stmt,':correo',$correo);
        oci_bind_by_name ($stmt,':telefono',$telefono);
        oci_bind_by_name ($stmt,':activo',$activo);
        oci_bind_b_name ($stmt,':id',$id);
        if (oci_execute($stmt,OCI_DEFAULT)){
            echo"Cambios realizados";
        }else{
            echo"Favor de verifixar los datos";
        }
}
    }
?>