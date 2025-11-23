<?php
class ResidenteController {

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 3) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }

    public function dashboard() {
        $this->verificarSesion();
        include __DIR__ . '/../views/residente/dashboard.php';
    }
}
