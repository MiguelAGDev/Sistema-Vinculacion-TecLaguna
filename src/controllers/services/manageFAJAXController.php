<?php
/**
 * API para obtener datos de flyers via AJAX
 * Ruta: index.php?url=api/flyer
 */

require_once ROOT_PATH.'/src/models/adminFlyersManageModel.php';

class FlyerApiController {
    private $model;

    public function __construct() {
        $this->model = new adminFlyersManageModel();
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Router interno para las acciones de la API
     */
    public function handleRequest($action = null) {
        $action = $action ?? ($_GET['action'] ?? 'getFlyer');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        switch ($action) {
            case 'getFlyer':
                $this->getFlyer($id);
                break;
            case 'aprobar':
                $this->aprobar($id);
                break;
            case 'rechazar':
                $this->rechazar($id);
                break;
            case 'getAll':
                $this->getAll();
                break;
            default:
                $this->jsonResponse(['error' => 'Acción no válida'], 400);
        }
    }

    /**
     * Obtiene un flyer específico por ID
     */
    private function getFlyer($id) {
        if (!$id) {
            $this->jsonResponse(['error' => 'ID no proporcionado'], 400);
            return;
        }

        $flyer = $this->model->getFlyerById($id);

        if ($flyer) {
            // Formatear fecha para mostrar
            $flyer['FECHA_FORMATEADA'] = date('d/m/Y', strtotime($flyer['FECHA_CREACION']));
            $this->jsonResponse(['success' => true, 'data' => $flyer]);
        } else {
            $this->jsonResponse(['error' => 'Flyer no encontrado'], 404);
        }
    }

    /**
     * Aprueba un flyer
     */
    private function aprobar($id) {
        if (!$id) {
            $this->jsonResponse(['error' => 'ID no proporcionado'], 400);
            return;
        }

        $result = $this->model->aprobarFlyer($id);

        if ($result) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Publicación aprobada correctamente',
                'flyerId' => $id
            ]);
        } else {
            $this->jsonResponse(['error' => 'Error al aprobar'], 500);
        }
    }

    /**
     * Rechaza un flyer
     */
    private function rechazar($id) {
        if (!$id) {
            $this->jsonResponse(['error' => 'ID no proporcionado'], 400);
            return;
        }

        $result = $this->model->rechazarFlyer($id);

        if ($result) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Publicación rechazada',
                'flyerId' => $id
            ]);
        } else {
            $this->jsonResponse(['error' => 'Error al rechazar'], 500);
        }
    }

    /**
     * Obtiene todos los flyers pendientes
     */
    private function getAll() {
        $flyers = $this->model->getFlyersPendientes();
        $total = $this->model->contarPendientes();

        $this->jsonResponse([
            'success' => true,
            'data' => $flyers,
            'total' => $total
        ]);
    }

    /**
     * Helper para enviar respuestas JSON
     */
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}