<?php
// src/models/FlyerCreateModel.php

class FlyerCreateModel {
    private $jsonFile = '../data/flyer.json';
    private $uploadDir = '../public/assets/img/img_for_flyers/';
    
    // Mapeos de datos
    private $carreras = [
        'sco' => 'Sistemas Computacionales',
        'mct' => 'Mecatrónica',
        'ind' => 'Industrial'
    ];
    
    private $grupos = [
        'res' => 'Residente',
        'egr' => 'Egresado'
    ];

    public function __construct() {
        // Crear directorio de imágenes si no existe
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        
        // Crear directorio de datos si no existe
        $dataDir = dirname($this->jsonFile);
        if (!file_exists($dataDir)) {
            mkdir($dataDir, 0755, true);
        }
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
        $required = ['title', 'bus_name', 'salary', 'career', 'group'];
        
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
     * Procesa las imágenes subidas
     * @param array $files Array de archivos $_FILES['imagen']
     * @param string $busName Nombre de la empresa
     * @return array Resultado del procesamiento
     */
    public function processImages($files, $busName) {
        $urlImagenes = [];
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Si no hay imágenes, retornar éxito con array vacío
        if (empty($files['name'][0])) {
            return ['success' => true, 'images' => []];
        }

        $numImages = count($files['name']);

        for ($i = 0; $i < $numImages; $i++) {
            $nombreOriginal = $files['name'][$i];
            $rutaTemporal = $files['tmp_name'][$i];
            $error = $files['error'][$i];
            
            if ($error === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
                
                // Validar extensión
                if (!in_array($extension, $extensionesPermitidas)) {
                    return [
                        'success' => false, 
                        'error' => 'Solo se permiten imágenes (jpg, jpeg, png, gif, webp).'
                    ];
                }
                
                // Limpiar nombre de empresa para el archivo
                $busNameClean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $busName);
                
                // Crear nombre único
                $nombreUnico = uniqid($busNameClean . '_flyer_', true) . '.' . $extension;
                $rutaDestino = $this->uploadDir . $nombreUnico;
                
                // Mover archivo
                if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                    $urlImagenes[] = $nombreUnico;
                } else {
                    return [
                        'success' => false, 
                        'error' => 'Error al subir la imagen. Verifica los permisos del servidor.'
                    ];
                }
            } elseif ($error !== UPLOAD_ERR_NO_FILE) {
                // Si hay un error que no sea "no hay archivo"
                return [
                    'success' => false, 
                    'error' => 'Error en la carga de imagen: código ' . $error
                ];
            }
        }

        return ['success' => true, 'images' => $urlImagenes];
    }

    /**
     * Guarda los datos del flyer en JSON
     * @param array $data Datos a guardar
     * @return array Resultado de la operación
     */
    public function saveFlyer($data) {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if ($jsonData === false) {
            return [
                'success' => false, 
                'message' => 'Error al codificar los datos.'
            ];
        }
        
        if (file_put_contents($this->jsonFile, $jsonData)) {
            return [
                'success' => true, 
                'message' => '✅ ¡Flyer publicado exitosamente!'
            ];
        } else {
            return [
                'success' => false, 
                'message' => '❌ Error al guardar el flyer. Verifica los permisos del servidor.'
            ];
        }
    }
}