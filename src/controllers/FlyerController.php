<?php
// src/controllers/FlyerCreateController.php
require_once SERVICE_PATH . '/PurifierService.php';

class FlyerController {
    private $model;

    public function __construct() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Muestra el formulario de creación
     */
    public function create() {
        require_once __DIR__ . '/../models/flyerCreateModel.php';
        $this->model = new FlyerCreateModel();
        // Preparar datos para la vista
        $viewData = [
            'mensaje' => $_SESSION['mensaje'] ?? '',
            'tipo_mensaje' => $_SESSION['tipo_mensaje'] ?? '',
            'form_data' => $_SESSION['form_data'] ?? [],
            'carreras' => $this->model->getCarreras(),
            'grupos' => $this->model->getGrupos()
        ];
        
        // Limpiar mensajes de sesión después de leerlos
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
        unset($_SESSION['form_data']);
        
        // Cargar la vista
        require_once __DIR__ . '/../views/flyerCreateView.php';
    }

    /**
     * Procesa y guarda el formulario
     */
    public function store() {
        require_once __DIR__ . '/../models/flyerCreateModel.php';
        $this->model = new FlyerCreateModel();
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=flyer/create');
            exit;
        }

        // Recopilar datos del formulario
        $formData = [
            'bus_name' => trim($_POST['bus_name'] ?? ''),
            'title' => trim($_POST['title'] ?? ''),
            'abstract' => $_POST['abstract'] ?? '', // TinyMCE puede tener HTML
            'end_date' => $_POST['end_date'] ?? '',
            'salary' => trim($_POST['salary'] ?? ''),
            'career' => $_POST['career'] ?? '',
            'group' => $_POST['group'] ?? ''
        ];

        //PURIFICACION
        $purifierService = new PurifierService();

        $formData['abstract'] = $purifierService->getPurifiedHtml($formData['abstract']);

        $formData['bus_name'] = htmlspecialchars($formData['bus_name']);
        $formData['title'] = htmlspecialchars($formData['title']);

        //extraer imagen 1
        $imageUrl = $this->getFirstImageUrl($formData['abstract']);

        // Validar datos
        $validation = $this->model->validateData($formData);
        if (!$validation['success']) {
            $_SESSION['mensaje'] = $validation['error'];
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['form_data'] = $formData;
            header('Location: /index.php?url=flyer/create');
            exit;
        }

        // Preparar datos para guardar
        $flyerData = [
            "Titulo" => $formData['title'],
            "Descripcion" => $formData['abstract'],
            "Imagen" => $imageUrl,
            "Id" => '1'
        ];

        // Guardar en la bd
        $saveResult = $this->model->saveFlyer($flyerData);
        
        $_SESSION['mensaje'] = $saveResult['message'];
        $_SESSION['tipo_mensaje'] = $saveResult['success'] ? 'success' : 'error';
        
        // Si fue exitoso, limpiar formulario
        if ($saveResult['success']) {
            $savedImageUrls = $this->getAllImagesUrl($formData['abstract']);
            $this->cleanImgs($savedImageUrls);
            unset($_SESSION['form_data']);
            header('Location: /index.php?url=flyer/create');
        } else {
            $_SESSION['form_data'] = $formData;
        }
        exit;
    }

    public function getFirstImageUrl(string $html): string{
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');

        if($images->length > 0 ){
            $firstImage = $images->item(0);
            return $firstImage->getAttribute('src');
        }

        return ''; // <- Poner imagen por default o obligar a subir una imagen
    }

    public function getAllImagesUrl(string $html): array {
        $urls = [];
        if (empty($html)) return $urls;

        $dom = new DOMDocument();
        // El hack de codificación sigue siendo buena práctica
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        $images = $dom->getElementsByTagName('img');
        
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (!empty($src)) {
                // OPCIONAL: Aquí podrías limpiar la URL para obtener solo el nombre
                // Pero es mejor hacerlo en la función de comparación.
                $urls[] = $src;
            }
        }
        return $urls;
    }

    private function cleanImgs(array $finalUrls) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $upload_dir = UPLOADS_PATH . 'img_flyers/';

        // 1. Crear un array simple que contenga SOLO los nombres de archivo finales
        // De: ['uploads/img/foto1.png', ...]  -> A: ['foto1.png', ...]
        $finalFilenames = array_map(function($url) {
            return basename($url);
        }, $finalUrls);

        if (isset($_SESSION['temp_images'])) {
            foreach ($_SESSION['temp_images'] as $index => $img) {
                
                // Obtenemos el nombre del archivo actual de la sesión
                $sessionFilename = basename($img['filename']); // o basename($img['url'])

                // 2. COMPARACIÓN ROBUSTA: ¿Está este nombre de archivo en la lista final?
                if (!in_array($sessionFilename, $finalFilenames)) {
                    
                    // No está en el HTML final -> Borrar
                    $filePath = $upload_dir . $img['filename'];
                    
                    if (file_exists($filePath) && strpos($filePath, $upload_dir) === 0) {
                        unlink($filePath);
                    }
                }
                
                // Eliminar de la sesión (ya sea que se guardó o se borró)
                unset($_SESSION['temp_images'][$index]);
            }
        }
    }

    public function search(){
        // Cargar el modelo
        require_once __DIR__ . '/../models/FlyerSearchModel.php';
        $model = new FlyerSearchModel();
        
        // Procesar filtros desde GET
        $filters = [];
        
        // Obtener IDs de empresas
        if (isset($_GET['tag_empresa_id']) && is_array($_GET['tag_empresa_id'])) {
            $filters['empresa_ids'] = array_filter(
                array_map('intval', $_GET['tag_empresa_id'])
            );
        }
        
        // Buscar flyers
        $flyers = $model->searchFlyers($filters);
        
        // Obtener información de empresas filtradas
        $empresas_filtradas = [];
        if (!empty($filters['empresa_ids'])) {
            $empresas_filtradas = $model->getEmpresasByIds($filters['empresa_ids']);
        }
        
        // Contar resultados
        $total_resultados = $model->contarResultados($filters);
        
        // Preparar datos para la vista
        $viewData = [
            'flyers' => $flyers,
            'filters' => $filters,
            'empresas_filtradas' => $empresas_filtradas,
            'total_resultados' => $total_resultados,
            'tiene_filtros' => !empty($filters)
        ];
        
        // Cargar la vista
        require_once __DIR__ . '/../views/flyerSearchView.php';
    }

    public function view() {
        require_once __DIR__ . '/../models/flyerViewModel.php';
        $model = new FlyerViewModel();

        // 1. Obtener y validar el ID
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            // Si el ID no es válido, redirigir al buscador
            header('Location: /index.php?url=flyer/search');
            exit;
        }

        // 2. Cargar datos del Flyer principal
        $flyer = $model->getFlyerById($id);

        // 3. Verificar si existe. Si es null, redirigimos antes de cargar la vista.
        if (!$flyer) {
            header('Location: /index.php?url=flyer/search');
            exit;
        }

        // 4. (Opcional) Incrementar contador de visitas
        $model->incrementViews($id);

        // 5. Preparar el array de datos para la vista ($viewData)
        // Nota: Pasamos el ID actual a getRecentFlyers para excluirlo de la lista sugerida
        $viewData = [
            'flyer'            => $flyer,
            'carreras'         => $model->getCarrerasByFlyer($id),        // Retorna array vacío por ahora (ver abajo)
            'tipos_estudiante' => $model->getTiposEstudianteByFlyer($id), // Retorna array vacío por ahora (ver abajo)
            'recent_flyers'    => $model->getRecentFlyers($id)            // Barra lateral
        ];

        // 6. Cargar la vista
        require_once __DIR__ . '/../views/flyerDetailView.php';
    }
}