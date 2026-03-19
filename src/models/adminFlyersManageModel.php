<?php
// src/models/FlyerCreateModel.php
require_once DATABASE_PATH;
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
            WHERE f.ESTADO IS NULL   -- SOLO PENDIENTES por que cuando estan pendietes por default son null
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
     public function aprobarFlyer($id) {
    try {
        //se conecta a la base de datos 
        $conn = $this->db->conectar();
        //sentencia que se va a ejecutar  
        $sql = "UPDATE FLYER SET estado = 2 WHERE FLAYER_ID = :id";
        //se prepara la sentencia
        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ":id", $id);
        //se ejecuta y a su vez se guarda en una variable el renglon
        $resultado = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stid);
        //retorna el resultado
        return $resultado;

    } catch (Exception $e) {
        error_log("Error al aprobar flyer: " . $e->getMessage());
        return false;
    }
}

    
    /**
     * Rechaza un flyer (lo oculta del administrador)
     * practicamente ahce lo mismo que aprobar pero ahora el estado es 1 que quiere decir que no se aprobo
     */
     public function rechazarFlyer($id) {
       try {
        $conn = $this->db->conectar();

        $sql = "UPDATE FLYER SET estado = 1 WHERE FLAYER_ID = :id";

        $stid = oci_parse($conn, $sql);
        oci_bind_by_name($stid, ":id", $id);

        $resultado = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stid);

        return $resultado;

    } catch (Exception $e) {
        error_log("Error al rechazar el flyer: " . $e->getMessage());
        return false;
    }
     }
    
    /**
     * Cuenta flyers pendientes
     * en este metodo igual hacemos una cosnutla al abase de datos pero ejecuta un count cuando el estado es ju8ll asi 
     * sabemos cuantos flyer estan pendientes
     */
    public function contarPendientes() {
        // $sql = "SELECT COUNT(*) as total FROM FLYER WHERE ESTADO = 0 OR ACTIVO IS NULL";
        $sql = "SELECT COUNT(*) as TOTAL FROM FLYER
                WHERE  ESTADO is NULL";
        
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
   /**
     * Actualiza el Flyer
     * si encontrmoa alguna irregularidad podemos modficarlo y subir alguno falta de ortografia, 
     */
   public function updateFlyer($id, $titulo, $descripcion, $imagenNueva = null) {
    $conn = $this->db->conectar();
    //validamos si hay una iamgen que cabias para ejecutar una sentencia distinta 
    if ($imagenNueva) {
        $sql = "UPDATE flyer 
                SET titulo = :titulo,
                    descripcion = :descripcion,
                    url_imagen = :img
                WHERE flayer_id = :id";
    } else {
        $sql = "UPDATE flyer 
                SET titulo = :titulo,
                    descripcion = :descripcion
                WHERE flayer_id = :id";
    }

    $stid = oci_parse($conn, $sql);

    // Bind normales
    oci_bind_by_name($stid, ":titulo", $titulo);
    oci_bind_by_name($stid, ":descripcion", $descripcion);
    oci_bind_by_name($stid, ":id", $id);

    if ($imagenNueva) {
        oci_bind_by_name($stid, ":img", $imagenNueva);
    }

    $ok = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

    // Logs útiles
    error_log("UPDATE flyer ejecutado. ID: $id, OK: " . json_encode($ok));

    oci_free_statement($stid);

    return $ok;
}

}
?>