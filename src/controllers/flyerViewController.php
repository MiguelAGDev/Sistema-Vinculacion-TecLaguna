<?php
// src/controllers/FlyerViewController.php

require_once __DIR__ . '/../models/FlyerViewModel.php';

class FlyerViewController {
    private $model;

    public function __construct() {
        $this->model = new FlyerViewModel();
    }

    /**
     * Muestra el detalle de un flyer específico
     */
    public function show() {
        // Obtener ID de la URL (para futuro con BD)
        $flyerId = $_GET['id'] ?? null;
        
        // Obtener datos del modelo
        $result = $this->model->getFlyerData($flyerId);
        
        // Preparar datos para la vista
        $viewData = [
            'flyer' => $result['data'],
            'error' => $result['error'],
            'carreras' => $result['carreras'],
            'grupos' => $result['grupos'],
            'model' => $this->model // Pasar modelo para métodos auxiliares
        ];
        
        // Cargar la vista
        require_once __DIR__ . '/../views/flyer_detail.php';
    }

    /**
     * Lista todos los flyers disponibles
     */
    public function index() {
        // Obtener todos los flyers
        $flyers = $this->model->getAllFlyers();
        
        // Preparar datos para la vista
        $viewData = [
            'flyers' => $flyers,
            'carreras' => $this->model->getCarreras(),
            'grupos' => $this->model->getGrupos(),
            'model' => $this->model
        ];
        
        // Cargar la vista
        require_once __DIR__ . '/../views/flyer_list.php';
    }
}