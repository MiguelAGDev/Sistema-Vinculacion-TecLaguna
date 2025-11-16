<?php
    require_once __DIR__ . '/../../config/config.php';
    class imgController{
        public function uploadImage(){
            header('Content-Type: application/json');

            if(empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK){
                http_response_code(400);
                echo json_encode(['error'=> 'Error de subida']);
                exit;
            }

            $file = $_FILES['file'];

            //Validaciones 
            $mime_type = mime_content_type($file['tmp_name']);
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size_bytes = 5 * 1024 * 1024; //Limite del archivo (5Mb)

            if(!in_array($mime_type, $allowed_mimes)){
                http_response_code(400);
                echo json_encode(['error'=> 'Archivo no permitido']);
                exit;
            }

            if($file['size'] > $max_size_bytes){
                http_response_code(400);
                echo json_encode(['error'=> 'Archivo demasiado grande (Max. 5MB']);
                exit;
            }

            //Guardado
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_').'.'.strtolower($extension);
            $target_file = UPLOADS_PATH.'img_flyers/'.$filename;
            $public_url = PUBLIC_IMG_FLYERS.$filename;

            if(!is_dir(UPLOADS_PATH)){
                mkdir(UPLOADS_PATH, 0777 , true);
            }

            if(move_uploaded_file($file['tmp_name'], $target_file)){
                echo json_encode(['location'=> $public_url], JSON_UNESCAPED_SLASHES);
            }else{
                http_response_code(400);
                echo json_encode(['error'=> 'Error al guardar la imagen']);
            }
        }
    }
    