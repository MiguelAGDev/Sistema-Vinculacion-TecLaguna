<?php
// src/models/FlyerCreateModel.php
require_once __DIR__. '/../../database/Conexion.php';

class adminFlyersManageModel {
    private $db;
    
    public function __construct() {
        $this->db = new Conexion();
    }
    
    /**
     * Obtiene todos los flyers pendientes de moderación con info de empresa
     */
    public function getFlyersPendientes() {
        $sql = "SELECT 
                    f.FLAYER_ID,
                    f.TITULO,
                    f.DESCRIPCION,
                    f.FECHA_CREACION,
                    f.URL_IMAGEN,
                    e.ID_EMPRESA,
                    e.NOMBRE_EMPRESA
                FROM FLYER f
                INNER JOIN EMPRESA e ON f.ID_EMPRESA = e.ID_EMPRESA
                ORDER BY f.FECHA_CREACION DESC";
        
        try {
            // 1. Conectar (igual que en tu saveFlyer)
            $conn = $this->db->conectar();

            // 2. Preparar la sentencia
            $stid = oci_parse($conn, $sql);
            if (!$stid) {
                $e = oci_error($conn);
                throw new Exception($e['message']);
            }

            // 3. Ejecutar
            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                throw new Exception($e['message']);
            }

            $flyers = [];

            // 4. Recorrer los resultados (Reemplazo de while fetch_assoc)
            // OCI_ASSOC: Devuelve un array asociativo (claves con nombre de columna)
            // OCI_RETURN_NULLS: Asegura que traiga columnas vacías como null para no romper el array
            while (($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS)) != false) {
                // Nota: Oracle devuelve las claves en MAYÚSCULAS por defecto (ej: $row['TITULO'])
                $flyers[] = $row;
            }

            // 5. Liberar memoria
            oci_free_statement($stid);

            return $flyers;

        } catch(Exception $e) {
            // Retornamos array vacío para que la vista no falle
            return [];
        }
    }
    
    /**
     * Obtiene un flyer específico por ID con datos de empresa
     */
    public function getFlyerById($id) {
        $sql = "SELECT 
                    f.FLAYER_ID,
                    f.TITULO,
                    f.DESCRIPCION,
                    f.FECHA_CREACION,
                    f.URL_IMAGEN,
                    e.ID_EMPRESA,
                    e.NOMBRE_EMPRESA
                FROM FLYER f
                INNER JOIN EMPRESA e ON f.ID_EMPRESA = e.ID_EMPRESA
                WHERE f.FLAYER_ID = :id"; // Cambiamos ? por :id
        
        try {
            // 1. Conectar
            $conn = $this->db->conectar();

            // 2. Preparar
            $stid = oci_parse($conn, $sql);
            if (!$stid) {
                $e = oci_error($conn);
                throw new Exception($e['message']);
            }

            // 3. Bindear (Vincular parámetro)
            // No necesitamos especificar el tipo "i", Oracle lo maneja.
            oci_bind_by_name($stid, ":id", $id);

            // 4. Ejecutar
            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                throw new Exception($e['message']);
            }
            
            // 5. Obtener el resultado
            $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS+OCI_RETURN_LOBS);

            // 6. Liberar memoria
            oci_free_statement($stid);
            
            // Si $row es false (no encontró nada), devolvemos null
            return $row ? $row : null;

        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Aprueba un flyer (lo hace visible para todos)
     */
    // public function aprobarFlyer($id) {
    //     $sql = "UPDATE FLAYER SET ACTIVO = 1 WHERE FLAYER_ID = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $id);
    //     return $stmt->execute();
    // }
    
    /**
     * Rechaza un flyer (lo oculta del administrador)
     */
    // public function rechazarFlyer($id) {
    //     $sql = "UPDATE FLAYER SET ACTIVO = -1 WHERE FLAYER_ID = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $id);
    //     return $stmt->execute();
    // }
    
    /**
     * Cuenta flyers pendientes
     */
    public function contarPendientes() {
        // $sql = "SELECT COUNT(*) as total FROM FLAYER WHERE ACTIVO = 0 OR ACTIVO IS NULL";
        $sql = "SELECT COUNT(*) as TOTAL FROM FLYER";
        
        try {
            // 1. Conectar
            $conn = $this->db->conectar();

            // 2. Preparar
            $stid = oci_parse($conn, $sql);
            if (!$stid) {
                $e = oci_error($conn);
                throw new Exception($e['message']);
            }

            // 3. Ejecutar
            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                throw new Exception($e['message']);
            }

            // 4. Obtener el resultado
            // Usamos oci_fetch_assoc que equivale a fetch_assoc de MySQL
            $row = oci_fetch_assoc($stid);
            
            // 5. Liberar memoria
            oci_free_statement($stid);

            // VALIDACIÓN:
            if ($row) {
                // ¡OJO AQUÍ! Oracle devuelve las claves en MAYÚSCULAS
                return $row['TOTAL']; 
            }
            
            return 0;

        } catch (Exception $e) {
            return 0;
        }
    }
}
?>