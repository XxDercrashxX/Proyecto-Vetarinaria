<?php
class Dueno {
    private $conn;
    private $table_name = "duenos";

    public $id;
    public $nombre;
    public $telefono;
    public $email;
    public $direccion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear dueño
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET nombre=:nombre, telefono=:telefono, email=:email, direccion=:direccion";
        
        $stmt = $this->conn->prepare($query);
        
        // Validar y sanitizar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los dueños
    public function leer() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un dueño por ID
    public function leerUno() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->direccion = $row['direccion'];
            return true;
        }
        return false;
    }

    // Actualizar dueño
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nombre=:nombre, telefono=:telefono, email=:email, direccion=:direccion 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar dueño
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

        // Validar teléfono (solo números y guiones)
        if (!empty($this->telefono) && !preg_match("/^[0-9\-]+$/", $this->telefono)) {
            $errores[] = "El teléfono solo puede contener números y guiones";
        }

        // Validar email
        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El formato del email no es válido";
        }

        return $errores;
    }

    // Obtener mascotas del dueño
    public function obtenerMascotas() {
        $query = "SELECT * FROM pacientes WHERE id_dueno = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>