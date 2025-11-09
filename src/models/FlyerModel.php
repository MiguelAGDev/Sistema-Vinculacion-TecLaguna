<?php
class FlyerModel {
    
    // Mapeos (esto podría ir en config/mappings.php también)
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
     * Obtiene los datos de un flyer específico.
     * En el futuro, $id se usará para buscar en la BD.
     * Por ahora, lee el JSON.
     */
    public function getFlyerData($id) {
        // En un futuro, $id decidirá qué JSON leer o qué fila de la BD buscar
        $jsonFile = '../data/flyer.json'; // Puse el JSON en una carpeta /data/ privada
        $data = null;
        $error = '';

        if (file_exists($jsonFile)) {
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode($jsonContent, true);
            
            if ($data === null) {
                $error = 'Error al leer el archivo JSON';
            }
        } else {
            $error = 'Archivo JSON no encontrado';
        }

        // Retornamos todo lo que la vista necesitará
        return [
            'data' => $data,
            'error' => $error,
            'carreras' => $this->carreras,
            'grupos' => $this->grupos
        ];
    }
}