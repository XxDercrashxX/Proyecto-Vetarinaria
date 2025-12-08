<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

class CitaController {
    private $cita;
    
    public function __construct($db) {
        $this->cita = new Cita($db);
    }

    public function listarCitas() {
        $stmt = $this->cita->leer();
        return $stmt;
    }

    public function obtenerCita($id) {
        $this->cita->id = $id;
        if($this->cita->leerUno()) {
            return $this->cita;
        }
        return null;
    }

    public function crearCita($datos) {
        $this->cita->fecha = $datos['fecha'];
        $this->cita->hora = $datos['hora'];
        $this->cita->id_paciente = $datos['id_paciente'];
        $this->cita->id_dueno = $datos['id_dueno'];
        $this->cita->motivo = $datos['motivo'];
        $this->cita->diagnostico = $datos['diagnostico'];
        $this->cita->estado = $datos['estado'];

        $errores = $this->cita->validarDatos();
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }

        if($this->cita->crear()) {
            return ['success' => true, 'message' => 'Cita creada correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al crear la cita']];
    }

    public function actualizarCita($id, $datos) {
        $this->cita->id = $id;
        
        if(!$this->cita->leerUno()) {
            return ['success' => false, 'errors' => ['Cita no encontrada']];
        }

        $this->cita->fecha = $datos['fecha'];
        $this->cita->hora = $datos['hora'];
        $this->cita->id_paciente = $datos['id_paciente'];
        $this->cita->id_dueno = $datos['id_dueno'];
        $this->cita->motivo = $datos['motivo'];
        $this->cita->diagnostico = $datos['diagnostico'];
        $this->cita->estado = $datos['estado'];

        $errores = $this->cita->validarDatos();
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }

        if($this->cita->actualizar()) {
            return ['success' => true, 'message' => 'Cita actualizada correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al actualizar la cita']];
    }

    public function eliminarCita($id) {
        $this->cita->id = $id;
        
        if(!$this->cita->leerUno()) {
            return ['success' => false, 'errors' => ['Cita no encontrada']];
        }

        if($this->cita->eliminar()) {
            return ['success' => true, 'message' => 'Cita eliminada correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al eliminar la cita']];
    }
}
?>