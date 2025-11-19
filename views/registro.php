<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../public/css/styles.css"> 
</head>
<body class="page-center">

<div class="register-container">
    
    <h2>INICIAR REGISTRO</h2>

    <form id="formRegistro" action="../controllers/AuthController.php" method="post" autocomplete="off">
        <input type="hidden" name="action" value="register">

        <!-- NOMBRE -->
        <div class="input-group">
            <input type="text" id="nombre_fake" placeholder="Nombre del usuario" autocomplete="new-name">
            <input type="hidden" id="nombre" name="nombre">
            <i class="fas fa-user icon"></i>
        </div>

        <!-- APELLIDO -->
        <div class="input-group">
            <input type="text" id="apellido_fake" placeholder="Apellido del usuario" autocomplete="new-name">
            <input type="hidden" id="apellido" name="apellido">
            <i class="fas fa-user icon"></i>
        </div>

        <!-- EMAIL -->
        <div class="input-group">
            <input type="text" id="email_fake" placeholder="Correo electrónico" autocomplete="new-email">
            <input type="hidden" id="email" name="email">
            <i class="fas fa-envelope icon"></i>
        </div>

        <!-- PASSWORD -->
        <div class="input-group">
            <input type="password" id="password_fake" placeholder="Agregar tu contraseña" autocomplete="new-password">
            <input type="hidden" id="password" name="password">
            <i class="fas fa-lock icon"></i>
        </div>

        <div class="button-group">
            <button type="button" class="btn-cancel" onclick="window.location.href='/index.php'">Cancelar</button>
            <button type="submit" class="btn-submit">Registrarse</button>
        </div>

    </form>
</div>

<script>
// PASAR VALORES A LOS INPUTS REALES ANTES DE ENVIAR
document.getElementById("formRegistro").addEventListener("submit", function(event) {
    let nombre = document.getElementById("nombre_fake").value.trim();
    let apellido = document.getElementById("apellido_fake").value.trim();
    let email = document.getElementById("email_fake").value.trim();
    let password = document.getElementById("password_fake").value.trim();

    if (!nombre || !apellido || !email || !password) {
        event.preventDefault();
        alert("Por favor, llena todos los campos.");
        return;
    }

    document.getElementById("nombre").value = nombre;
    document.getElementById("apellido").value = apellido;
    document.getElementById("email").value = email;
    document.getElementById("password").value = password;
});
</script>

</body>
</html>
