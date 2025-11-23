<?php
class EgresadoController {

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 4) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }

    public function home() {
        $this->verificarSesion();
        include __DIR__ . '/../views/egresado/home.php';
    }
}
