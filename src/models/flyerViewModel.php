<?php
// src/models/FlyerViewModel.php

require_once DATABASE_PATH;

class FlyerViewModel {
    private $db;
    
    public function __construct() {
        $this->db = new Conexion();
    }
    
    /**
     * Obtiene un flyer por ID con toda su información
     * @param int $id - ID del flyer
     * @return array|null - Datos del flyer o null
     */
    public function getFlyerById($id) {
        try {
            $conn = $this->db->conectar();
            
            $sql = "SELECT 
                        f.FLAYER_ID,
                        f.TITULO,
                        f.DESCRIPCION,
                        f.FECHA_CREACION,
                        f.URL_IMAGEN,
                        e.ID_EMPRESA,
                        e.NOMBRE_EMPRESA
                    FROM FLAYER f
                    INNER JOIN EMPRESA e ON f.ID_EMPRESA = e.ID_EMPRESA
                    WHERE f.FLAYER_ID = :id";
            
            $stid = oci_parse($conn, $sql);
            if (!$stid) {
                $e = oci_error($conn);
                throw new Exception($e['message']);
            }
            
            oci_bind_by_name($stid, ":id", $id);
            
            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                throw new Exception($e['message']);
            }
            
            $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS);
            oci_free_statement($stid);
            
            return $row ? $row : null;
            
        } catch (Exception $e) {
            error_log("Error en getFlyerById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene las carreras asociadas a un flyer (FUTURO)
     * @param int $flyer_id - ID del flyer
     * @return array - Array de carreras
     */
    public function getCarrerasByFlyer($flyer_id) {
        // FUTURO: Cuando implementes la tabla FLAYER_CARRERA
        /*
        try {
            $conn = $this->db->conectar();
            
            $sql = "SELECT 
                        c.ID_CARRERA,
                        c.NOMBRE_CARRERA
                    FROM CARRERA c
                    INNER JOIN FLAYER_CARRERA fc ON c.ID_CARRERA = fc.ID_CARRERA
                    WHERE fc.FLAYER_ID = :flyer_id";
            
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":flyer_id", $flyer_id);
            oci_execute($stid);
            
            $carreras = [];
            while ($row = oci_fetch_assoc($stid)) {
                $carreras[] = $row;
            }
            
            oci_free_statement($stid);
            return $carreras;
            
        } catch (Exception $e) {
            return [];
        }
        */
        return [];
    }
    
    /**
     * Obtiene los tipos de estudiante asociados a un flyer (FUTURO)
     * @param int $flyer_id - ID del flyer
     * @return array - Array de tipos de estudiante
     */
    public function getTiposEstudianteByFlyer($flyer_id) {
        // FUTURO: Cuando implementes la tabla FLAYER_TIPO
        /*
        try {
            $conn = $this->db->conectar();
            
            $sql = "SELECT 
                        te.ID_TIPO_ESTUDIANTE,
                        te.NOMBRE_TIPO
                    FROM TIPO_ESTUDIANTE te
                    INNER JOIN FLAYER_TIPO ft ON te.ID_TIPO_ESTUDIANTE = ft.ID_TIPO_ESTUDIANTE
                    WHERE ft.FLAYER_ID = :flyer_id";
            
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":flyer_id", $flyer_id);
            oci_execute($stid);
            
            $tipos = [];
            while ($row = oci_fetch_assoc($stid)) {
                $tipos[] = $row;
            }
            
            oci_free_statement($stid);
            return $tipos;
            
        } catch (Exception $e) {
            return [];
        }
        */
        return [];
    }
    
    /**
     * Obtiene las 3 publicaciones más recientes excluyendo la actual
     * @param int $exclude_id - ID del flyer a excluir
     * @return array - Array de flyers recientes
     */
    public function getRecentFlyers($exclude_id = null) {
        try {
            $conn = $this->db->conectar();
            
            $sql = "SELECT * FROM (
                        SELECT 
                            f.FLAYER_ID,
                            f.TITULO,
                            f.URL_IMAGEN,
                            e.NOMBRE_EMPRESA
                        FROM FLAYER f
                        INNER JOIN EMPRESA e ON f.ID_EMPRESA = e.ID_EMPRESA";
            
            if ($exclude_id !== null) {
                $sql .= " WHERE f.FLAYER_ID != :exclude_id";
            }
            
            $sql .= " ORDER BY f.FECHA_CREACION DESC
                    )
                    WHERE ROWNUM <= 3";
            
            $stid = oci_parse($conn, $sql);
            
            if ($exclude_id !== null) {
                oci_bind_by_name($stid, ":exclude_id", $exclude_id);
            }
            
            oci_execute($stid);
            
            $flyers = [];
            while ($row = oci_fetch_assoc($stid)) {
                $flyers[] = $row;
            }
            
            oci_free_statement($stid);
            return $flyers;
            
        } catch (Exception $e) {
            error_log("Error en getRecentFlyers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Incrementa el contador de vistas (FUTURO - opcional)
     * @param int $id - ID del flyer
     * @return bool
     */
    public function incrementViews($id) {
        // FUTURO: Si quieres agregar un campo de vistas/contador
        /*
        try {
            $conn = $this->db->conectar();
            
            $sql = "UPDATE FLAYER 
                    SET VISTAS = VISTAS + 1 
                    WHERE FLAYER_ID = :id";
            
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":id", $id);
            
            $r = oci_execute($stid);
            oci_free_statement($stid);
            
            return $r;
            
        } catch (Exception $e) {
            return false;
        }
        */
        return true;
    }
}
?>