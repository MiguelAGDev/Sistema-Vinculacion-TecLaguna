<?php
    class ApiController{
        public function flyer(){
            require_once SERVICES_PATH.'manageFAJAXController.php';
            $ver = new FlyerApiController();
            $ver->handleRequest();
        }

        public function upimg(){
            require_once SERVICES_PATH.'imgController.php';
            $controller = new imgController();
            $controller->uploadImage();
        }

        public function delimg(){
            require_once SERVICES_PATH.'imgController.php';
            $controller = new imgController();
            $controller->cleanTempImg();
        }
    }