<?php
require_once MODELS_PATH . 'adminFlyersManageModel.php';

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
        require_once VIEWS_PATH . 'adminFlyersManageView.php';
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
        require_once VIEWS_PATH . 'adminFlyerManageView.php';
    }
    
    /**
     * Aprueba un flyer
     * pasamos el id como parametro tenemos un objeto del modelo y mandamos llamar la funcion aprobarFlyer
     * en la sesion en el campo msj se le asigna 'Publicacion Aprobada' o ''Error en la publicacion dependiendo de 
     * si fue satisfactorio o no
     */
    public function aprobar($id) {
    if ($id && $this->model->aprobarFlyer($id)) {
        $_SESSION['mensaje'] = 'Publicación aprobada correctamente';
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = 'Error al aprobar la publicación';
        $_SESSION['tipo_mensaje'] = 'error';
    }
    //vuelve a vargar la pagina y la actualiza, vuelve a ejecutar el filtro y ya no aparece por que se abrobo
    header('Location: /index.php?url=main/manage');
    exit;
}

    
    /**
     * Rechaza un flyer
     * hace basicamente lo mismo que el de arriba pero amdna llamar al otro metodo
     */
    public function rechazar($id) {
    if ($id && $this->model->rechazarFlyer($id)) {
        $_SESSION['mensaje'] = 'Publicación rechazada';
        $_SESSION['tipo_mensaje'] = 'warning';
    } else {
        $_SESSION['mensaje'] = 'Error al rechazar la publicación';
        $_SESSION['tipo_mensaje'] = 'error';
    }

    header('Location: /index.php?url=main/manage');
    exit;
}
   
    /**
     * actualiza el flyer
     * este metodo lo usamos para mandar recibir lo que la vista de editar envia cuando el admin quiere etitarlo antes de
     * aceptarlo o rechazarlo
     */
   public function update() {
    // Asegurar POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['mensaje'] = "Método no permitido";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: index.php?url=main/manage");
        exit;
    }

    // Recoger valores
    $id = $_POST['id'] ?? null; //este es un input que esta hidden
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    //valida la id para evitar errores
    if (!$id) {
        $_SESSION['mensaje'] = "ID inválido";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: index.php?url=main/manage");
        exit;
    }

    // Procesar imagen (si suben)
    $imagenNueva = null;
    if (!empty($_FILES['imagen']['name'])) {
        // Carpeta pública de uploads (ajusta si tu public path es otro)
        $uploadsDir = ROOT_PATH . '/public/uploads/'; // es donde guardamos las imagenes
        if (!is_dir($uploadsDir)) {
            if (!mkdir($uploadsDir, 0777, true)) {
                error_log("No se pudo crear directorio de uploads: $uploadsDir");
            }
        }

        
        $basename = time() . "_" . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['imagen']['name'])); //el basename basicamente lo que hace es que se quede nada mas con el nombre base del archivo 
                                   //el replace como ya sabemos remplaza los signos por un guin bajo
        $rutaCompleta = $uploadsDir . $basename; //aqui usa la ruta de las aimgene sy le concantena el nombre base que ya formamos

        $moved = move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta); //guarda la imagen en un archivo temporal y la mueeve a la carpeta definitiva
        if (!$moved) { //nad amas es para manejar el error de la imagen
            error_log("Error move_uploaded_file: tmp=" . ($_FILES['imagen']['tmp_name'] ?? 'NULL') . " dest=$rutaCompleta");
            $_SESSION['mensaje'] = "No se pudo guardar la imagen en el servidor (revisa permisos).";
            $_SESSION['tipo_mensaje'] = "error";
            header("Location: index.php?url=main/manage");
            exit;
        }

        // ruta que guardamos para que sea accesible por el navegador
        $imagenNueva = '/uploads/' . $basename;
    }

    // Llamar al modelo (ya cargado en el constructor como $this->model)
    $result = $this->model->updateFlyer($id, $titulo, $descripcion, $imagenNueva);

    if ($result) {
        $_SESSION['mensaje'] = "Flyer actualizado correctamente";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        // Obtener último error de Oracle si es posible
        // (el modelo no expone el error, así que registramos algo útil)
        error_log("Error al ejecutar updateFlyer en modelo para ID: $id");
        $_SESSION['mensaje'] = "Error al actualizar el flyer (revisa logs del servidor)";
        $_SESSION['tipo_mensaje'] = "error";
    }

    header("Location: index.php?url=main/manage");
    exit;
}

    public function editar($id) {
    require_once MODELS_PATH . 'adminFlyersManageModel.php';
    $model = new adminFlyersManageModel();

    // Obtener datos del flyer por medio de la id
    $flyer = $model->getFlyerById($id);

    if (!$flyer) {
        echo "Flyer no encontrado";
        return;
    }

    // Datos para la vista
    $viewData = [
        'flyer' => $flyer
    ];

   require_once VIEWS_PATH . 'editarFlyer.php';
}
}
?>