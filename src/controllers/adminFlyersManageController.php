<?php
require_once ROOT_PATH.'/src/models/adminFlyersManageModel.php';

class adminFlyersManageController {
    private $model;
    
    public function __construct() {
        $this->model = new adminFlyersManageModel();

        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Maneja las acciones del controlador
     */
    public function handleRequest() {
        $action = $_GET['action'] ?? 'index';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        switch ($action) {
            case 'aprobar':
                $this->aprobar($id);
                break;
            case 'rechazar':
                $this->rechazar($id);
                break;
            case 'ver':
                $this->ver($id);
                break;
            default:
                $this->index();
        }
    }
    
    /**
     * Muestra la lista de flyers pendientes
     */
    public function index() {
        $flyers = $this->model->getFlyersPendientes();
        $totalPendientes = $this->model->contarPendientes();
        
        // Si hay flyers, selecciona el primero por defecto
        $flyerSeleccionado = null;
        if (!empty($flyers)) {
            $idSeleccionado = $_GET['id'] ?? $flyers[0]['FLAYER_ID'];
            $flyerSeleccionado = $this->model->getFlyerById($idSeleccionado);
        }
        
        $viewData = [
            'flyers' => $flyers,
            'flyerSeleccionado' => $flyerSeleccionado,
            'totalPendientes' => $totalPendientes,
            // Agregamos los mensajes de sesión
            'mensaje' => $_SESSION['mensaje'] ?? '',
            'tipo_mensaje' => $_SESSION['tipo_mensaje'] ?? ''
        ];

        // 2. Limpiamos los mensajes flash
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        
        // 3. Llamamos a la vista
        // Asegúrate de que el nombre del archivo coincida con el que tienes en tu carpeta views
        require_once ROOT_PATH.'/src/views/adminFLyersManageView.php';
    }
    
    /**
     * Obtiene datos de un flyer específico
     */
    public function ver($id) {
        if (!$id) {
            header('Location: main/main');
            exit;
        }
        
        $flyer = $this->model->getFlyerById($id);
        $flyers = $this->model->getFlyersPendientes();
        $totalPendientes = $this->model->contarPendientes();
        
        // Preparamos los datos
        $viewData = [
            'flyers' => $flyers,
            'flyerSeleccionado' => $flyer,
            'totalPendientes' => $totalPendientes,
            'mensaje' => $_SESSION['mensaje'] ?? '',
            'tipo_mensaje' => $_SESSION['tipo_mensaje'] ?? ''
        ];

        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        
        // Llamamos a la misma vista (asumiendo que usas la misma interfaz para ver detalles)
        require_once __DIR__ . '/../views/adminFlyerManageView.php';
    }
    
    /**
     * Aprueba un flyer
     */
    public function aprobar($id) {
        if ($id && $this->model->aprobarFlyer($id)) {
            $_SESSION['mensaje'] = 'Publicación aprobada correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al aprobar la publicación';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        header('Location: main/main');
        exit;
    }
    
    /**
     * Rechaza un flyer
     */
    public function rechazar($id) {
        if ($id && $this->model->rechazarFlyer($id)) {
            $_SESSION['mensaje'] = 'Publicación rechazada';
            $_SESSION['tipo_mensaje'] = 'warning';
        } else {
            $_SESSION['mensaje'] = 'Error al rechazar la publicación';
            $_SESSION['tipo_mensaje'] = 'error';
        }
        header('Location: main/main');
        exit;
    }
}
?>