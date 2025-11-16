<?php
// src/models/FlyerViewModel.php

class FlyerViewModel {
    private $jsonFile = '../data/flyer.json';
    
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

    /**
     * Obtiene los mapeos de carreras
     */
    public function getCarreras() {
        return $this->carreras;
    }

    /**
     * Obtiene los mapeos de grupos
     */
    public function getGrupos() {
        return $this->grupos;
    }

    /**
     * Obtiene el nombre completo de una carrera
     */
    public function getCarreraName($codigo) {
        return $this->carreras[$codigo] ?? $codigo;
    }

    /**
     * Obtiene el nombre completo de un grupo
     */
    public function getGrupoName($codigo) {
        return $this->grupos[$codigo] ?? $codigo;
    }

    /**
     * Obtiene los datos de un flyer específico
     * @param int|null $id ID del flyer (para futuro con BD)
     * @return array Datos del flyer
     */
    public function getFlyerData($id = null) {
        $data = null;
        $error = '';

        // Verificar si el archivo existe
        if (!file_exists($this->jsonFile)) {
            $error = 'No se encontró el archivo de datos del flyer.';
            return [
                'data' => null,
                'error' => $error,
                'carreras' => $this->carreras,
                'grupos' => $this->grupos
            ];
        }

        // Leer el archivo JSON
        $jsonContent = file_get_contents($this->jsonFile);
        $data = json_decode($jsonContent, true);
        
        // Verificar errores en el JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = 'Error al procesar los datos: ' . json_last_error_msg();
            $data = null;
        }

        return [
            'data' => $data,
            'error' => $error,
            'carreras' => $this->carreras,
            'grupos' => $this->grupos
        ];
    }

    /**
     * Obtiene todos los flyers (preparado para BD en futuro)
     * @return array Lista de flyers
     */
    public function getAllFlyers() {
        $result = $this->getFlyerData();
        
        if ($result['data'] && $result['error'] === '') {
            // Por ahora retorna un array con un solo flyer
            // En futuro: SELECT * FROM flyers ORDER BY fecha_inicio DESC
            return [$result['data']];
        }
        
        return [];
    }

    /**
     * Formatea una fecha al formato DD/MM/YYYY
     */
    public function formatDate($date) {
        if ($date === 'Sin limite' || empty($date)) {
            return 'Sin límite';
        }
        
        return date('d/m/Y', strtotime($date));
    }
}