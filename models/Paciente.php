<?php
// models/Paciente.php
class Paciente {
    private $conn;
    private $table_name = "pacientes";

    public $id;
    public $nombre;
    public $especie;
    public $raza;
    public $edad;
    public $sexo;
    public $diagnostico;
    public $hospitalizado;
    public $id_dueno;
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear paciente
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET nombre=:nombre, especie=:especie, raza=:raza, edad=:edad, 
                     sexo=:sexo, diagnostico=:diagnostico, hospitalizado=:hospitalizado, 
                     id_dueno=:id_dueno, imagen=:imagen";
        
        $stmt = $this->conn->prepare($query);
        
        // Validar y sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->especie = htmlspecialchars(strip_tags($this->especie));
        $this->raza = htmlspecialchars(strip_tags($this->raza));
        $this->sexo = htmlspecialchars(strip_tags($this->sexo));
        $this->diagnostico = htmlspecialchars(strip_tags($this->diagnostico));
        
        // Bind parameters
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":especie", $this->especie);
        $stmt->bindParam(":raza", $this->raza);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":sexo", $this->sexo);
        $stmt->bindParam(":diagnostico", $this->diagnostico);
        $stmt->bindParam(":hospitalizado", $this->hospitalizado);
        $stmt->bindParam(":id_dueno", $this->id_dueno);
        $stmt->bindParam(":imagen", $this->imagen);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los pacientes
    public function leer() {
        $query = "SELECT p.*, d.nombre as nombre_dueno 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN duenos d ON p.id_dueno = d.id 
                  ORDER BY p.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo paciente
    public function leerUno() {
        $query = "SELECT p.*, d.nombre as nombre_dueno 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN duenos d ON p.id_dueno = d.id 
                  WHERE p.id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->especie = $row['especie'];
            $this->raza = $row['raza'];
            $this->edad = $row['edad'];
            $this->sexo = $row['sexo'];
            $this->diagnostico = $row['diagnostico'];
            $this->hospitalizado = $row['hospitalizado'];
            $this->id_dueno = $row['id_dueno'];
            $this->imagen = $row['imagen'];
            return true;
        }
        return false;
    }

    // Actualizar paciente
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nombre=:nombre, especie=:especie, raza=:raza, edad=:edad, 
                     sexo=:sexo, diagnostico=:diagnostico, hospitalizado=:hospitalizado, 
                     id_dueno=:id_dueno, imagen=:imagen 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Validar y sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->especie = htmlspecialchars(strip_tags($this->especie));
        $this->raza = htmlspecialchars(strip_tags($this->raza));
        $this->sexo = htmlspecialchars(strip_tags($this->sexo));
        $this->diagnostico = htmlspecialchars(strip_tags($this->diagnostico));
        
        // Bind parameters
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":especie", $this->especie);
        $stmt->bindParam(":raza", $this->raza);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":sexo", $this->sexo);
        $stmt->bindParam(":diagnostico", $this->diagnostico);
        $stmt->bindParam(":hospitalizado", $this->hospitalizado);
        $stmt->bindParam(":id_dueno", $this->id_dueno);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar paciente
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

        // Validar nombre (solo letras y espacios)
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->nombre)) {
            $errores[] = "El nombre solo puede contener letras y espacios";
        }

        // Validar especie (solo letras)
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->especie)) {
            $errores[] = "La especie solo puede contener letras";
        }

        // Validar raza (solo letras y espacios)
        if (!empty($this->raza) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->raza)) {
            $errores[] = "La raza solo puede contener letras y espacios";
        }

        // Validar edad (número positivo)
        if ($this->edad < 0) {
            $errores[] = "La edad debe ser un número positivo";
        }

        // Validar sexo (solo letras)
        if (!empty($this->sexo) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $this->sexo)) {
            $errores[] = "El sexo solo puede contener letras";
        }

        return $errores;
    }
}
?>