<?php
/**
 * Controlador API para la barra de búsqueda
 * /src/controllers/services/SearchbarApiController.php
 */

require_once ROOT_PATH . '/database/Conexion.php';

class SearchbarController {
    
    private $conexion;
    
    public function __construct() {
        $this->conexion = new Conexion();
    }
    
    /**
     * Método principal que maneja las acciones
     */
    public function handleRequest() {
        header('Content-Type: application/json');
        
        try {
            $action = isset($_GET['action']) ? $_GET['action'] : '';
            
            switch ($action) {
                case 'search':
                    $this->search();
                    break;
                    
                default:
                    $this->sendError('Acción no válida');
                    break;
            }
            
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }
    
    /**
     * Buscar tags y retornar sugerencias
     */
    private function search() {
        try {
            $query = isset($_GET['query']) ? trim($_GET['query']) : '';
            
            if (empty($query) || strlen($query) < 2) {
                $this->sendSuccess([
                    'empresas' => [],
                    'carreras' => [],
                    'tipos_estudiante' => []
                ]);
                return;
            }
            
            $conn = $this->conexion->conectar();
            
            // Preparar respuesta
            $response = [
                'empresas' => [],
                'carreras' => [],
                'tipos_estudiante' => []
            ];
            
            // Dividir query en tags individuales (separadas por espacios)
            $tags = explode(' ', $query);
            $tags = array_filter(array_map('trim', $tags));
            
            if (empty($tags)) {
                $tags = [$query];
            }
            
            // Procesar cada tag
            foreach ($tags as $tag) {
                $tag_busqueda = str_replace(' ', '_', $tag);
                $patron = '%' . strtoupper($tag_busqueda) . '%';
                
                // ========== BUSCAR EN EMPRESAS ==========
                $sql_empresa = "SELECT 
                                    e.ID_EMPRESA,
                                    e.NOMBRE_EMPRESA,
                                    COUNT(f.FLAYER_ID) as TOTAL_FLAYERS
                                FROM EMPRESA e
                                LEFT JOIN FLAYER f ON e.ID_EMPRESA = f.ID_EMPRESA
                                WHERE UPPER(REPLACE(e.NOMBRE_EMPRESA, ' ', '_')) LIKE :patron
                                GROUP BY e.ID_EMPRESA, e.NOMBRE_EMPRESA
                                HAVING COUNT(f.FLAYER_ID) > 0
                                ORDER BY COUNT(f.FLAYER_ID) DESC";
                
                $stid_empresa = oci_parse($conn, $sql_empresa);
                oci_bind_by_name($stid_empresa, ':patron', $patron);
                oci_execute($stid_empresa);
                
                while ($row = oci_fetch_assoc($stid_empresa)) {
                    $tag_formato = str_replace(' ', '_', $row['NOMBRE_EMPRESA']);
                    
                    // Verificar si ya existe
                    $existe = false;
                    foreach ($response['empresas'] as $emp) {
                        if ($emp['id'] == $row['ID_EMPRESA']) {
                            $existe = true;
                            break;
                        }
                    }
                    
                    if (!$existe) {
                        $response['empresas'][] = [
                            'id' => $row['ID_EMPRESA'],
                            'tag' => $tag_formato,
                            'count' => intval($row['TOTAL_FLAYERS'])
                        ];
                    }
                }
                oci_free_statement($stid_empresa);
                
                // ========== BUSCAR EN CARRERAS (Para implementación futura) ==========
                /*
                $sql_carrera = "SELECT 
                                    c.ID_CARRERA,
                                    c.NOMBRE_CARRERA,
                                    COUNT(DISTINCT f.FLAYER_ID) as TOTAL_FLAYERS
                                FROM CARRERA c
                                LEFT JOIN FLAYER_CARRERA fc ON c.ID_CARRERA = fc.ID_CARRERA
                                LEFT JOIN FLAYER f ON fc.FLAYER_ID = f.FLAYER_ID
                                WHERE UPPER(REPLACE(c.NOMBRE_CARRERA, ' ', '_')) LIKE :patron
                                GROUP BY c.ID_CARRERA, c.NOMBRE_CARRERA
                                HAVING COUNT(DISTINCT f.FLAYER_ID) > 0
                                ORDER BY COUNT(DISTINCT f.FLAYER_ID) DESC";
                
                $stid_carrera = oci_parse($conn, $sql_carrera);
                oci_bind_by_name($stid_carrera, ':patron', $patron);
                oci_execute($stid_carrera);
                
                while ($row = oci_fetch_assoc($stid_carrera)) {
                    $tag_formato = str_replace(' ', '_', $row['NOMBRE_CARRERA']);
                    
                    $existe = false;
                    foreach ($response['carreras'] as $car) {
                        if ($car['id'] == $row['ID_CARRERA']) {
                            $existe = true;
                            break;
                        }
                    }
                    
                    if (!$existe) {
                        $response['carreras'][] = [
                            'id' => $row['ID_CARRERA'],
                            'tag' => $tag_formato,
                            'count' => intval($row['TOTAL_FLAYERS'])
                        ];
                    }
                }
                oci_free_statement($stid_carrera);
                */
                
                // ========== BUSCAR EN TIPOS DE ESTUDIANTE (Para implementación futura) ==========
                /*
                $sql_tipo = "SELECT 
                                te.ID_TIPO_ESTUDIANTE,
                                te.NOMBRE_TIPO,
                                COUNT(DISTINCT f.FLAYER_ID) as TOTAL_FLAYERS
                            FROM TIPO_ESTUDIANTE te
                            LEFT JOIN FLAYER_TIPO ft ON te.ID_TIPO_ESTUDIANTE = ft.ID_TIPO_ESTUDIANTE
                            LEFT JOIN FLAYER f ON ft.FLAYER_ID = f.FLAYER_ID
                            WHERE UPPER(REPLACE(te.NOMBRE_TIPO, ' ', '_')) LIKE :patron
                            GROUP BY te.ID_TIPO_ESTUDIANTE, te.NOMBRE_TIPO
                            HAVING COUNT(DISTINCT f.FLAYER_ID) > 0
                            ORDER BY COUNT(DISTINCT f.FLAYER_ID) DESC";
                
                $stid_tipo = oci_parse($conn, $sql_tipo);
                oci_bind_by_name($stid_tipo, ':patron', $patron);
                oci_execute($stid_tipo);
                
                while ($row = oci_fetch_assoc($stid_tipo)) {
                    $tag_formato = str_replace(' ', '_', $row['NOMBRE_TIPO']);
                    
                    $existe = false;
                    foreach ($response['tipos_estudiante'] as $tipo) {
                        if ($tipo['id'] == $row['ID_TIPO_ESTUDIANTE']) {
                            $existe = true;
                            break;
                        }
                    }
                    
                    if (!$existe) {
                        $response['tipos_estudiante'][] = [
                            'id' => $row['ID_TIPO_ESTUDIANTE'],
                            'tag' => $tag_formato,
                            'count' => intval($row['TOTAL_FLAYERS'])
                        ];
                    }
                }
                oci_free_statement($stid_tipo);
                */
            }
            
            // Ordenar resultados por count (descendente)
            usort($response['empresas'], function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            usort($response['carreras'], function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            usort($response['tipos_estudiante'], function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            // Limitar resultados a los 10 más relevantes por categoría
            $response['empresas'] = array_slice($response['empresas'], 0, 10);
            $response['carreras'] = array_slice($response['carreras'], 0, 10);
            $response['tipos_estudiante'] = array_slice($response['tipos_estudiante'], 0, 10);
            
            $this->sendSuccess($response);
            
        } catch (Exception $e) {
            $this->sendError($e->getMessage());
        }
    }
    
    /**
     * Enviar respuesta exitosa
     */
    private function sendSuccess($data, $message = '') {
        echo json_encode([
            'success' => true,
            'data' => $data,
            'message' => $message
        ]);
        exit;
    }
    
    /**
     * Enviar respuesta de error
     */
    private function sendError($message) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit;
    }
    
    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->desconectar();
        }
    }
}

// Este archivo es llamado desde ApiController->search()
// No necesita verificación de llamada directa
?>