<?php
// 1. Incluimos el modelo
require_once '../src/models/FlyerModel.php';

// 2. Creamos una instancia y obtenemos los datos
$flyerModel = new FlyerModel();
$flyerId = $_GET['id'] ?? null; // Obtenemos el ID del flyer desde la URL
$result = $flyerModel->getFlyerData($flyerId);

// 3. "Extraemos" los datos a variables para que la vista pueda usarlos
// $result['data'] se convierte en $data
// $result['error'] se convierte en $error
// etc.
extract($result);

// 4. Cargamos la vista (que ahora tiene acceso a $data, $error, $carreras, $grupos)
require '../src/views/flyer.php';