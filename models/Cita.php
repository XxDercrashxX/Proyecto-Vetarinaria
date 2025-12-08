<?php
class Cita {
    private $conn;
    private $table_name = "citas";

    public $id;
    public $fecha;
    public $hora;
    public $id_paciente;
    public $id_dueno;
    public $motivo;
    public $diagnostico;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear cita
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET fecha=:fecha, hora=:hora, id_paciente=:id_paciente, 
                     id_dueno=:id_dueno, motivo=:motivo, diagnostico=:diagnostico, estado=:estado";
        
        $stmt = $this->conn->prepare($query);
        
        $this->motivo = htmlspecialchars(strip_tags($this->motivo));
        $this->diagnostico = htmlspecialchars(strip_tags($this->diagnostico));
        
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":hora", $this->hora);
        $stmt->bindParam(":id_paciente", $this->id_paciente);
        $stmt->bindParam(":id_dueno", $this->id_dueno);
        $stmt->bindParam(":motivo", $this->motivo);
        $stmt->bindParam(":diagnostico", $this->diagnostico);
        $stmt->bindParam(":estado", $this->estado);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todas las citas
    public function leer() {
        $query = "SELECT c.*, p.nombre as nombre_paciente, d.nombre as nombre_dueno 
                  FROM " . $this->table_name . " c
                  LEFT JOIN pacientes p ON c.id_paciente = p.id
                  LEFT JOIN duenos d ON c.id_dueno = d.id
                  ORDER BY c.fecha DESC, c.hora DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer una cita por ID
    public function leerUno() {
        $query = "SELECT c.*, p.nombre as nombre_paciente, d.nombre as nombre_dueno 
                  FROM " . $this->table_name . " c
                  LEFT JOIN pacientes p ON c.id_paciente = p.id
                  LEFT JOIN duenos d ON c.id_dueno = d.id
                  WHERE c.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->fecha = $row['fecha'];
            $this->hora = $row['hora'];
            $this->id_paciente = $row['id_paciente'];
            $this->id_dueno = $row['id_dueno'];
            $this->motivo = $row['motivo'];
            $this->diagnostico = $row['diagnostico'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }

    // Actualizar cita
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                 SET fecha=:fecha, hora=:hora, id_paciente=:id_paciente, 
                     id_dueno=:id_dueno, motivo=:motivo, diagnostico=:diagnostico, estado=:estado
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->motivo = htmlspecialchars(strip_tags($this->motivo));
        $this->diagnostico = htmlspecialchars(strip_tags($this->diagnostico));
        
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":hora", $this->hora);
        $stmt->bindParam(":id_paciente", $this->id_paciente);
        $stmt->bindParam(":id_dueno", $this->id_dueno);
        $stmt->bindParam(":motivo", $this->motivo);
        $stmt->bindParam(":diagnostico", $this->diagnostico);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar cita
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Validaciones
    public function validarDatos() {
        $errores = [];

        // Validar fecha (no puede ser en el pasado)
        $fecha_actual = date('Y-m-d');
        if ($this->fecha < $fecha_actual) {
            $errores[] = "La fecha no puede ser en el pasado";
        }

        return $errores;
    }
}
?>