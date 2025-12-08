<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

class PacienteController {
    private $paciente;
    
    public function __construct($db) {
        $this->paciente = new Paciente($db);
    }

    public function listarPacientes() {
        $stmt = $this->paciente->leer();
        return $stmt;
    }

    public function obtenerPaciente($id) {
        $this->paciente->id = $id;
        if($this->paciente->leerUno()) {
            return $this->paciente;
        }
        return null;
    }

    public function crearPaciente($datos) {
        // Asignar datos
        $this->paciente->nombre = $datos['nombre'];
        $this->paciente->especie = $datos['especie'];
        $this->paciente->raza = $datos['raza'];
        $this->paciente->edad = $datos['edad'];
        $this->paciente->sexo = $datos['sexo'];
        $this->paciente->diagnostico = $datos['diagnostico'];
        $this->paciente->hospitalizado = $datos['hospitalizado'];
        $this->paciente->id_dueno = $datos['id_dueno'];
        $this->paciente->imagen = $datos['imagen'];

        // Validar datos
        $errores = $this->paciente->validarDatos();
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }

        // Crear paciente
        if($this->paciente->crear()) {
            return ['success' => true, 'message' => 'Paciente creado correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al crear el paciente']];
    }

    public function actualizarPaciente($id, $datos) {
        $this->paciente->id = $id;
        
        // Primero verificar que existe
        if(!$this->paciente->leerUno()) {
            return ['success' => false, 'errors' => ['Paciente no encontrado']];
        }

        // Asignar nuevos datos
        $this->paciente->nombre = $datos['nombre'];
        $this->paciente->especie = $datos['especie'];
        $this->paciente->raza = $datos['raza'];
        $this->paciente->edad = $datos['edad'];
        $this->paciente->sexo = $datos['sexo'];
        $this->paciente->diagnostico = $datos['diagnostico'];
        $this->paciente->hospitalizado = $datos['hospitalizado'];
        $this->paciente->id_dueno = $datos['id_dueno'];
        $this->paciente->imagen = $datos['imagen'];

        // Validar datos
        $errores = $this->paciente->validarDatos();
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }

        // Actualizar paciente
        if($this->paciente->actualizar()) {
            return ['success' => true, 'message' => 'Paciente actualizado correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al actualizar el paciente']];
    }

    public function eliminarPaciente($id) {
        $this->paciente->id = $id;
        
        // Verificar que existe
        if(!$this->paciente->leerUno()) {
            return ['success' => false, 'errors' => ['Paciente no encontrado']];
        }

        if($this->paciente->eliminar()) {
            return ['success' => true, 'message' => 'Paciente eliminado correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al eliminar el paciente']];
    }
}
?>