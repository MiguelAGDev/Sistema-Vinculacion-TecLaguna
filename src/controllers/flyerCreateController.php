<?php
// src/controllers/FlyerCreateController.php

require_once __DIR__ . '/../models/flyerCreateModel.php';

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

        // Validar datos
        $validation = $this->model->validateData($formData);
        if (!$validation['success']) {
            $_SESSION['mensaje'] = $validation['error'];
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['form_data'] = $formData;
            header('Location: /flyer/create');
            exit;
        }

        // Procesar imágenes
        $imageResult = $this->model->processImages(
            $_FILES['imagen'] ?? [], 
            $formData['bus_name']
        );
        
        if (!$imageResult['success']) {
            $_SESSION['mensaje'] = $imageResult['error'];
            $_SESSION['tipo_mensaje'] = 'error';
            $_SESSION['form_data'] = $formData;
            header('Location: /flyer/create');
            exit;
        }

        // Preparar datos para guardar
        $flyerData = [
            "Empresa" => $formData['bus_name'],
            "Titulo" => $formData['title'],
            "Descripcion" => $formData['abstract'],
            "Fecha_inicio" => date("Y-m-d"),
            "Fecha_fin" => !empty($formData['end_date']) ? $formData['end_date'] : 'Sin limite',
            "Sueldo" => $formData['salary'],
            "Imagenes" => $imageResult['images'],
            "Carrera" => $formData['career'],
            "Grupo" => $formData['group']
        ];

        // Guardar en JSON
        $saveResult = $this->model->saveFlyer($flyerData);
        
        $_SESSION['mensaje'] = $saveResult['message'];
        $_SESSION['tipo_mensaje'] = $saveResult['success'] ? 'success' : 'error';
        
        // Si fue exitoso, limpiar formulario
        if ($saveResult['success']) {
            unset($_SESSION['form_data']);
        } else {
            $_SESSION['form_data'] = $formData;
        }
        
        header('Location: /flyer/create');
        exit;
    }
}