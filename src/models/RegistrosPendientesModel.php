<?php

namespace models;

use Database\Conexion;

/**
 * Clase RegistroPendienteModel
 * * Gestiona las operaciones CRUD en la tabla temporal 'REGISTRO_PENDIENTE'.
 * Se encarga de almacenar, recuperar y eliminar los datos de usuarios 
 * que están en proceso de verificación por correo electrónico.
 * * @package Models
 */
class RegistroPendienteModel{

    /**
     * Instancia de conexión a la base de datos.
     * @var mixed|resource
     */
    private $db;

    /**
     * Constructor de la clase.
     * * Inicializa la propiedad $db estableciendo la conexión con Oracle.
     */
    public function __construct(){
        $this->db = new Conexion();
        $this->db = $this->db->conexión();
    }


    /**
     * Inserta un nuevo registro pendiente de verificación en la base de datos.
     * @param string $token       Token único de seguridad (hexadecimal).
     * @param string $json        Cadena JSON que contiene los datos del formulario de registro.
     * @param string $correo      Dirección de correo electrónico del usuario.
     * @param string $tipoUsuario Tipo de usuario (ej. 'residente', 'egresado').
     * @return bool               Retorna true si la inserción fue exitosa, false en caso de error.
     */
    public function crear($token, $json, $correo, $tipoUsuario){

        $sql = "INSERT INTO registro_pendiente (token, datos_json, correo, tipoUsuario)
                VALUES (:token, :json, :correo, :tipo)";

        $stmt = oci_parse($this->db, $sql);

        // VINCULAMOS LOS PARAMETORS 
        oci_bind_by_name($stmt, ':token', $token);
        oci_bind_by_name($stmt, ':json', $json);
        oci_bind_by_name($stmt, ':correo', $correo);
        oci_bind_by_name($stmt, ':tipo', $tipoUsuario);

        // Ejecutamos
        if(!oci_execute($stmt)){

            $e = oci_error($stmt);
            error_log("Error al insertar en REGISTRO_PENDIENTE: ".$e['message']);
            oci_free_statement($stmt);
            return false;
        }

        oci_free_statement($stmt);
        return true;
    }


    /**
     * Busca y recupera los datos de un registro pendiente mediante su token.
     * * Incluye el formateo de la fecha de expiración para su validación en PHP.
     * * @param string $token Token de verificación a buscar.
     * @return array|false  Retorna un array asociativo con los datos (token, json, correo, tipo, fecha) 
     * o false si no se encuentra o falla la consulta.
     */
    public function obtenerPorToken($token){
        $sql = "SELECT token, datos_json, correo, tipo_usuario,
                        TO_CHAR(fecha_expiracion, 'YYYY-MM-DD HH24:MI:SS') as FECHA_FMT
                        FROM REGISTRO_PENDIENTE 
                        WHERE token = :token";

        $stmt = oci_parse($this->db, $sql);
        oci_bind_by_name($stmt, ':token', $token);

        if(!oci_execute($stmt)){
            return false;
        }

        $fila = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $fila ?: false;


    }


    /**
     * Elimina un registro pendiente de la base de datos.
     * * Se debe llamar tras una activación exitosa o limpieza de tokens expirados.
     * @param string $token Token del registro a eliminar.
     * @return bool         Retorna true si la ejecución SQL fue exitosa (independientemente de si borró filas o no).
     */
    public function eliminar($token): bool{
        $sql = "DELETE FROM registro_pendiente WHERE token = :token";
    
        $stmt = oci_parse($this->db, $sql);
        oci_bind_by_name($stmt, ':token', $token);

        $resultado = oci_execute($stmt);
        oci_free_statement($stmt);

        return $resultado;

    }

}