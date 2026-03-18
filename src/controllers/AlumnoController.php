<?php
class AlumnoController {

    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 2) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }

    public function inicio() {
        $this->verificarSesion();
        include __DIR__ . '/../views/alumno/inicio.php';
    }
}

