<?php
// src/controllers/FlyerCreateController.php

require_once __DIR__ . '/../models/flyerCreateModel.php';
require_once SERVICE_PATH . 'PurifierService.php';

class FlyerCreateController {
    private $model;

    public function __construct() {
        $this->model = new FlyerCreateModel();
        
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Muestra el formulario de creación
     */
    public function create() {
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
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /flyer/create');
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
            header('Location: /index?ruta=flyer/create');
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
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['form_data'] = $formData;
        }
        
        header('Location: /index?ruta=flyer/create');
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
}