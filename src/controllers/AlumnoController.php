<?php
class AlumnoController {

    /**
     * Verifica si el usuario tiene una sesión activa como alumno
     */
    private function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 2) {
            header('Location: index.php?url=auth/login');
            exit;
        }
    }

    /**
     * Muestra la vista de inicio del alumno
     */

    public function inicio() {
        $this->verificarSesion();
        include __DIR__ . '/../views/alumno/inicio.php';
    }
}

