<?php
    require_once CONFIG_PATH;
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
            $target_file = UPLOADS_PATH.'/img_flyers/'.$filename;
            $public_url = PUBLIC_IMG_FLYERS.$filename;

            if(!is_dir(UPLOADS_PATH)){
                mkdir(UPLOADS_PATH, 0777 , true);
            }

            if(move_uploaded_file($file['tmp_name'], $target_file)){
                $_SESSION['temp_images'][] = [
                    'url' => $public_url,
                    'filename' => $filename
                ];

                echo json_encode(['location'=> $public_url], JSON_UNESCAPED_SLASHES);
            }else{
                http_response_code(400);
                echo json_encode(['error'=> 'Error al guardar la imagen']);
            }
        }

        public function cleanTempImg(){
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }

            if (isset($_SESSION['temp_images'])){
                foreach($_SESSION['temp_images'] as $img){
                    $filepath = UPLOADS_PATH . 'img_flyers/'.$img['filename'];

                    if(file_exists($filepath) && strpos($filepath, UPLOADS_PATH) === 0){
                        unlink($filepath);
                    }
                }
                unset ($_SESSION['temp_images']);
                http_response_code(204);
                exit;
            }
        }
    }
    