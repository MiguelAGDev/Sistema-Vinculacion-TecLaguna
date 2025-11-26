<?php
// src/models/FlyerCreateModel.php
require_once __DIR__. '/../../database/Conexion.php';
class FlyerCreateModel {
    
    // Mapeos de datos
    private $carreras = [
        '6' => 'Sistemas Computacionales',
        '9' => 'Mecatrónica',
        '7' => 'Industrial',
        '3' => 'Electrónica',
        '2' => 'Eléctrica',
        '8' => 'Mecánica',
        '4' => 'Energías Renovables',
        '10' => 'Química',
        '5' => 'Géstion Empresarial',
        '1' => 'Licenciatura en Administración'
    ];
    
    private $grupos = [
        'res' => 'Residente',
        'egr' => 'Egresado'
    ];

    private $db;
    public function __construct() {
        $this -> db = new Conexion();
    }

    /**
     * Obtiene las opciones de carreras
     */
    public function getCarreras() {
        return $this->carreras;
    }

    /**
     * Obtiene las opciones de grupos
     */
    public function getGrupos() {
        return $this->grupos;
    }

    /**
     * Valida los datos del formulario
     * @param array $data Datos a validar
     * @return array Resultado de la validación
     */
    public function validateData($data) {
        $required = ['title', 'career', 'group'];
        
        // Validar campos requeridos
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return [
                    'success' => false,
                    'error' => 'Por favor completa todos los campos obligatorios (*)'
                ];
            }
        }
        
        // Validar carrera
        if (!array_key_exists($data['career'], $this->carreras)) {
            return [
                'success' => false,
                'error' => 'La carrera seleccionada no es válida.'
            ];
        }
        
        // Validar grupo
        if (!array_key_exists($data['group'], $this->grupos)) {
            return [
                'success' => false,
                'error' => 'El tipo de trabajador seleccionado no es válido.'
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * Guarda los datos del flyer en JSON
     * @param array $data Datos a guardar
     * @return array Resultado de la operación
     */
    public function saveFlyer($data) {
        $sql  = "INSERT INTO flayer (
            titulo,
            descripcion,
            url_imagen,
            id_empresa
            ) VALUES (
             :titulo,
             :descripcion,
             :imagen,
             :id
            ) 
        ";

        try{
            $conn = $this->db->conectar();

            $stid = oci_parse($conn, $sql);

            oci_bind_by_name($stid, ':titulo', $data['Titulo']);
            oci_bind_by_name($stid, ':descripcion', $data['Descripcion']);
            oci_bind_by_name($stid, ':imagen', $data['Imagen']);
            oci_bind_by_name($stid, ':id', $data['Id']);

            $r = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

            if(!$r){
                $e = oci_error($stid);
                return ['success' => false, 'message' => "Error al guardar el flyer".$e['message']];
            }

            oci_free_statement($stid);
            
            return ['success' => true, 'message' => 'Flyer guardado con exito.'];
        }catch (Exception $e){
            return ['success' => false, 'message' => 'Error de bd'];
        }
    }
}