<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Dueno.php';
include_once '../models/Paciente.php';

$database = new Database();
$db = $database->getConnection();

$dueno = new Dueno($db);
$paciente = new Paciente($db);

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: duenos.php");
    exit();
}

$dueno->id = $id;
if (!$dueno->leerUno()) {
    header("Location: duenos.php?error=not_found");
    exit();
}

$mascotas = $dueno->obtenerMascotas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Dueño - VETEICA CLINIC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../public/css/styles.css"> 
</head>
<body>
    <header class="main-header">
        <nav class="main-nav">
            <a href="inicio.php" class="nav-logo">
                <img src="../public/css/img/logoVetica.png" alt="Veteica Clinic Logo">
            </a>
            <ul class="nav-links">
                <li><a href="inicio.php">INICIO</a></li>
                <li><a href="paciente.php">PACIENTE</a></li>
                <li><a href="duenos.php" class="link-active">DUEÑOS</a></li>
                <li><a href="citas.php">CITAS</a></li>
            </ul>
            <div class="nav-profile"><span><?php echo substr($_SESSION['user_nombre'], 0, 1); ?></span></div>
            
            <div class="profile">
                <h3>Perfil del administrador</h3>
                <div class="profile-header">
                    <div class="profile-avatar"><span><?php echo substr($_SESSION['user_nombre'], 0, 1); ?></span></div>
                    <strong><?php echo $_SESSION['user_nombre']; ?></strong>
                    <p><?php echo $_SESSION['user_email']; ?></p>
                </div>
                <div class="profile-body">
                    <label>Credencial:</label><div class="info-box">9494165149840401</div>
                    <label>Certificado:</label><div class="info-box">Veteterinario</div>
                    <label>Telefono:</label><div class="info-box">612 111 1111</div>
                </div>
                <div class="profile-footer"><a href="../logout.php">Cerrar sesión</a></div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="details-container">
            <h2 class="page-title" style="margin-top: 0;">Detalle de Dueño</h2>

            <div class="owner-header-section">
                <div class="owner-photo-container">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Foto del Dueño" class="owner-photo">
                </div>

                <div class="owner-info-data">
                    <h2 id="owner-name" style="text-align: left; margin: 0;"><?php echo htmlspecialchars($dueno->nombre); ?></h2>
                    <p id="owner-phone"><strong><?php echo htmlspecialchars($dueno->telefono); ?></strong></p>
                    <p id="owner-address"><?php echo htmlspecialchars($dueno->direccion); ?></p>
                </div>

                <div class="owner-email-container">
                    <p id="owner-email"><?php echo htmlspecialchars($dueno->email); ?></p>
                </div>
            </div>

            <hr class="section-divider">

            <h3 class="section-subtitle">Mascotas asociadas:</h3>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Especie</th>
                        <th>Raza</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($mascota = $mascotas->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mascota['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($mascota['especie']); ?></td>
                        <td><?php echo htmlspecialchars($mascota['raza']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="pdf-buttons" style="margin-top: 3rem;">
                <a href="#" class="btn-pdf">Descargar Ficha (PDF)</a>
                <a href="#" class="btn-pdf">Generar Carnet Veterinario (PDF)</a>
            </div>
        </div>
    </main>

    <footer class="main-footer">© 2025 Veterinaria Amor y lealtad</footer>

    <script>
      const profileIcon = document.querySelector(".nav-profile");
      const profileCard = document.querySelector(".profile");

      profileIcon.addEventListener("click", (e) => {
        e.stopPropagation();
        profileCard.classList.toggle("active");
      });

      window.addEventListener("click", (e) => {
        if (!profileCard.contains(e.target)) {
          profileCard.classList.remove("active");
        }
      });
    </script>
</body>
</html>