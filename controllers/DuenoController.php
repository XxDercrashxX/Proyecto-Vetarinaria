<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

class DuenoController {
    private $dueno;
    
    public function __construct($db) {
        $this->dueno = new Dueno($db);
    }

    public function listarDuenos() {
        $stmt = $this->dueno->leer();
        return $stmt;
    }

    public function obtenerDueno($id) {
        $this->dueno->id = $id;
        if($this->dueno->leerUno()) {
            return $this->dueno;
        }
        return null;
    }

    public function crearDueno($datos) {
        $this->dueno->nombre = $datos['nombre'];
        $this->dueno->telefono = $datos['telefono'];
        $this->dueno->email = $datos['email'];
        $this->dueno->direccion = $datos['direccion'];

        $errores = $this->dueno->validarDatos();
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }

        if($this->dueno->crear()) {
            return ['success' => true, 'message' => 'Dueño creado correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al crear el dueño']];
    }

    public function actualizarDueno($id, $datos) {
        $this->dueno->id = $id;
        
        if(!$this->dueno->leerUno()) {
            return ['success' => false, 'errors' => ['Dueño no encontrado']];
        }

        $this->dueno->nombre = $datos['nombre'];
        $this->dueno->telefono = $datos['telefono'];
        $this->dueno->email = $datos['email'];
        $this->dueno->direccion = $datos['direccion'];

        $errores = $this->dueno->validarDatos();
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }

        if($this->dueno->actualizar()) {
            return ['success' => true, 'message' => 'Dueño actualizado correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al actualizar el dueño']];
    }

    public function eliminarDueno($id) {
        $this->dueno->id = $id;
        
        if(!$this->dueno->leerUno()) {
            return ['success' => false, 'errors' => ['Dueño no encontrado']];
        }

        if($this->dueno->eliminar()) {
            return ['success' => true, 'message' => 'Dueño eliminado correctamente'];
        }
        return ['success' => false, 'errors' => ['Error al eliminar el dueño']];
    }
}
?>