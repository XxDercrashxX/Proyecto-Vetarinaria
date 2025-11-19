<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../public/css/styles.css"> 
</head>
<body class="page-center">

<div class="login-logo-container">
    <img src="../public/css/img/logoVetica.png" alt="Veteica Clinic Logo">
</div>

<div class="register-container">
    
    <h2>INICIAR SESIÓN</h2>

    <form action="../controllers/AuthController.php" method="post">
        <input type="hidden" name="action" value="login">
        
       <div class="input-group">
            <input type="email" id="email" name="email" placeholder="Correo electrónico" required autocomplete="new-email">
           <i class="fas fa-envelope icon"></i>
        </div>

        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Agregar tu contraseña" required autocomplete="new-password">
            <i class="fas fa-lock icon"></i>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">Entrar</button>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            <p>¿No tienes una cuenta? <a href="./views/registro.php">Regístrate aquí</a></p>
        </div>

    </form>
</div>

</body>
</html>