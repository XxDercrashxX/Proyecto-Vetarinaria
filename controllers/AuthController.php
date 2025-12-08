<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if($_POST['action'] == 'register') {
    $user->nombre = $_POST['nombre'];
    $user->apellido = $_POST['apellido'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    
    if($user->emailExists()) {
        echo "<script>alert('El email ya está registrado'); window.location.href='../views/registro.php';</script>";
    } else {
        if($user->register()) {
            echo "<script>alert('Registro exitoso'); window.location.href='/index.php';</script>";
        } else {
            echo "<script>alert('Error en el registro'); window.location.href='../views/registro.php';</script>";
        }
    }
    exit();
}

if($_POST['action'] == 'login') {
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    
    if($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_nombre'] = $user->nombre;
        $_SESSION['user_email'] = $user->email;
        header("Location: ../views/inicio.php");
        exit();
    } else {
        echo "<script>alert('Credenciales incorrectas'); window.location.href='/index.php';</script>";
    }
    exit();
}
?>