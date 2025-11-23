<?php
    class ApiController{
        public function flyer(){
            require_once ROOT_PATH.'/src/controllers/services/manageFAJAXController.php';
            $ver = new FlyerApiController();
            $ver->handleRequest();
        }

        public function upimg(){
            require_once ROOT_PATH.'/src/controllers/services/imgController.php';
            $controller = new imgController();
            $controller->uploadImage();
        }

        public function delimg(){
            require_once ROOT_PATH.'/src/controllers/services/imgController.php';
            $controller = new imgController();
            $controller->cleanTempImg();
        }
    }