<?php
// src/models/FlyerSearchModel.php

require_once DATABASE_PATH;

class FlyerSearchModel {
    private $db;
    
    public function __construct() {
        $this->db = new Conexion();
    }
    
    /**
     * Busca flyers aplicando filtros de tags
     * @param array $filters - Filtros de búsqueda (empresa_ids, carrera_ids, tipo_ids)
     * @return array - Array de flyers encontrados
     */
    public function searchFlyers($filters = []) {
        try {
            $conn = $this->db->conectar();
            
            // Query base
            $sql = "SELECT 
                        f.FLAYER_ID,
                        f.TITULO,
                        f.DESCRIPCION,
                        f.FECHA_CREACION,
                        f.URL_IMAGEN,
                        e.ID_EMPRESA,
                        e.NOMBRE_EMPRESA
                    FROM FLYER f
                    INNER JOIN EMPRESA e ON f.ID_EMPRESA = e.ID_EMPRESA";
            
            // Construir WHERE dinámicamente
            $where_conditions = [];
            $bind_params = [];
            
            // Filtro por empresas
            if (!empty($filters['empresa_ids']) && is_array($filters['empresa_ids'])) {
                $placeholders = [];
                foreach ($filters['empresa_ids'] as $index => $id) {
                    $param_name = ":empresa_id_$index";
                    $placeholders[] = $param_name;
                    $bind_params[$param_name] = intval($id);
                }
                $where_conditions[] = "e.ID_EMPRESA IN (" . implode(',', $placeholders) . ")";
            }
            
            // Filtro por carreras (FUTURO - comentado)
            /*
            if (!empty($filters['carrera_ids']) && is_array($filters['carrera_ids'])) {
                $placeholders = [];
                foreach ($filters['carrera_ids'] as $index => $id) {
                    $param_name = ":carrera_id_$index";
                    $placeholders[] = $param_name;
                    $bind_params[$param_name] = intval($id);
                }
                $where_conditions[] = "EXISTS (
                    SELECT 1 FROM FLAYER_CARRERA fc 
                    WHERE fc.FLAYER_ID = f.FLAYER_ID 
                    AND fc.ID_CARRERA IN (" . implode(',', $placeholders) . ")
                )";
            }
            */
            
            // Filtro por tipo de estudiante (FUTURO - comentado)
            /*
            if (!empty($filters['tipo_ids']) && is_array($filters['tipo_ids'])) {
                $placeholders = [];
                foreach ($filters['tipo_ids'] as $index => $id) {
                    $param_name = ":tipo_id_$index";
                    $placeholders[] = $param_name;
                    $bind_params[$param_name] = intval($id);
                }
                $where_conditions[] = "EXISTS (
                    SELECT 1 FROM FLAYER_TIPO ft 
                    WHERE ft.FLAYER_ID = f.FLAYER_ID 
                    AND ft.ID_TIPO_ESTUDIANTE IN (" . implode(',', $placeholders) . ")
                )";
            }
            */
            
            // Agregar WHERE si hay condiciones
            if (!empty($where_conditions)) {
                $sql .= " WHERE " . implode(' AND ', $where_conditions);
            }
            
            // Ordenar por fecha descendente
            $sql .= " ORDER BY f.FECHA_CREACION DESC";
            
            // Preparar statement
            $stid = oci_parse($conn, $sql);
            if (!$stid) {
                $e = oci_error($conn);
                throw new Exception($e['message']);
            }
            
            // Bindear parámetros
            foreach ($bind_params as $param => $value) {
                oci_bind_by_name($stid, $param, $bind_params[$param]);
            }
            
            // Ejecutar
            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                throw new Exception($e['message']);
            }
            
            // Obtener resultados
            $flyers = [];
            while (($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS)) != false) {
                $flyers[] = $row;
            }
            
            // Liberar
            oci_free_statement($stid);
            
            return $flyers;
            
        } catch (Exception $e) {
            error_log("Error en searchFlyers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene información de una empresa por ID
     * @param int $id - ID de la empresa
     * @return array|null - Datos de la empresa o null
     */
    public function getEmpresaById($id) {
        try {
            $conn = $this->db->conectar();
            
            $sql = "SELECT ID_EMPRESA, NOMBRE_EMPRESA 
                    FROM EMPRESA 
                    WHERE ID_EMPRESA = :id";
            
            $stid = oci_parse($conn, $sql);
            oci_bind_by_name($stid, ":id", $id);
            oci_execute($stid);
            
            $row = oci_fetch_assoc($stid);
            oci_free_statement($stid);
            
            return $row ? $row : null;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Obtiene información de múltiples empresas por IDs
     * @param array $ids - Array de IDs de empresas
     * @return array - Array de empresas
     */
    public function getEmpresasByIds($ids) {
        if (empty($ids) || !is_array($ids)) {
            return [];
        }
        
        try {
            $conn = $this->db->conectar();
            
            // Crear placeholders
            $placeholders = [];
            $bind_params = [];
            foreach ($ids as $index => $id) {
                $param_name = ":id_$index";
                $placeholders[] = $param_name;
                $bind_params[$param_name] = intval($id);
            }
            
            $sql = "SELECT ID_EMPRESA, NOMBRE_EMPRESA 
                    FROM EMPRESA 
                    WHERE ID_EMPRESA IN (" . implode(',', $placeholders) . ")";
            
            $stid = oci_parse($conn, $sql);
            
            // Bindear
            foreach ($bind_params as $param => $value) {
                oci_bind_by_name($stid, $param, $bind_params[$param]);
            }
            
            oci_execute($stid);
            
            $empresas = [];
            while ($row = oci_fetch_assoc($stid)) {
                $empresas[] = $row;
            }
            
            oci_free_statement($stid);
            return $empresas;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Cuenta el total de flyers que coinciden con los filtros
     * @param array $filters - Filtros de búsqueda
     * @return int - Total de flyers
     */
    public function contarResultados($filters = []) {
        try {
            $conn = $this->db->conectar();
            
            $sql = "SELECT COUNT(*) as TOTAL 
                    FROM FLYER f
                    INNER JOIN EMPRESA e ON f.ID_EMPRESA = e.ID_EMPRESA";
            
            $where_conditions = [];
            $bind_params = [];
            
            // Filtro por empresas
            if (!empty($filters['empresa_ids']) && is_array($filters['empresa_ids'])) {
                $placeholders = [];
                foreach ($filters['empresa_ids'] as $index => $id) {
                    $param_name = ":empresa_id_$index";
                    $placeholders[] = $param_name;
                    $bind_params[$param_name] = intval($id);
                }
                $where_conditions[] = "e.ID_EMPRESA IN (" . implode(',', $placeholders) . ")";
            }
            
            if (!empty($where_conditions)) {
                $sql .= " WHERE " . implode(' AND ', $where_conditions);
            }
            
            $stid = oci_parse($conn, $sql);
            
            foreach ($bind_params as $param => $value) {
                oci_bind_by_name($stid, $param, $bind_params[$param]);
            }
            
            oci_execute($stid);
            $row = oci_fetch_assoc($stid);
            oci_free_statement($stid);
            
            return $row ? intval($row['TOTAL']) : 0;
            
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>