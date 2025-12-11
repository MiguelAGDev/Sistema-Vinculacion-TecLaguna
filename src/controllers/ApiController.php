<?php
    class ApiController{
        /**
         * Maneja las solicitudes relacionadas con flyers a través de AJAX
         */
        public function flyer(){
            require_once ROOT_PATH.'/src/controllers/services/manageFAJAXController.php';
            $ver = new FlyerApiController();
            $ver->handleRequest();
        }

        /**
         * Maneja las solicitudes relacionadas con la carga y eliminación de imágenes a través de AJAX
         */

        public function upimg(){
            require_once ROOT_PATH.'/src/controllers/services/imgController.php';
            $controller = new imgController();
            $controller->uploadImage();
        }

        /**
         * Maneja la eliminación de imágenes temporales a través de AJAX
         */
        public function delimg(){
            require_once ROOT_PATH.'/src/controllers/services/imgController.php';
            $controller = new imgController();
            $controller->cleanTempImg();
        }
    }