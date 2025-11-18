<?php
class MainController {
    public function handleView($view) {
        switch ($view) {
            case 'agregarUsuario':
                $this->agregarUsuario();
                break;
            case 'flyers':
                $this->flyers();
                break;
            case 'login':
                $this->login();
                break;
            case 'main':
                $this->main();
                break;
            case 'perfil':
                $this->flyers();
                break;
            case 'usuarios':
                $this->usuarios();
                break;                
            default:
                $this->error();
                break;
        }
    }

    private function agregarUsuario() {
        include 'views/agregarUsuario.php';
    }

    private function flyers() {
        include 'views/flyers.php';
    }

    private function login() {
        include 'views/login.php';
    }
     private function main() {
        include 'views/main.php';
    }
     private function perfil() {
        include 'views/perfil.php';
    }
     private function usuarios() {
        include 'views/login.php';
    }
}