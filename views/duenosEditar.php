<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Dueno.php';
include_once '../controllers/DuenoController.php';

$database = new Database();
$db = $database->getConnection();
$duenoController = new DuenoController($db);

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: duenos.php");
    exit();
}

$dueno = $duenoController->obtenerDueno($id);

if (!$dueno) {
    header("Location: duenos.php?error=not_found");
    exit();
}

$errores = [];

if ($_POST) {
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'email' => $_POST['email'] ?? '',
        'direccion' => $_POST['direccion'] ?? ''
    ];

    $resultado = $duenoController->actualizarDueno($id, $datos);
    
    if ($resultado['success']) {
        header("Location: duenos.php?success=update");
        exit();
    } else {
        $errores = $resultado['errors'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Dueño - VETEICA CLINIC</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../public/css/styles.css" />
  </head>
  <body>
    <header class="main-header">
      <nav class="main-nav">
        <a href="inicio.php" class="nav-logo">
          <img
            src="../public/css/img/logoVetica.png"
            alt="Veteica Clinic Logo"
          />
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
            <label>Credencial:</label>
            <div class="info-box">9494165149840401</div>
            <label>Certificado:</label>
            <div class="info-box">Veteterinario</div>
            <label>Telefono:</label>
            <div class="info-box">612 111 1111</div>
          </div>
          <div class="profile-footer">
            <a href="../logout.php">Cerrar sesión</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container">
      <h2 class="page-title">
        Editar Dueño: <?php echo htmlspecialchars($dueno->nombre); ?>
      </h2>

      <?php if (!empty($errores)): ?>
        <div class="alert error">
          <ul>
            <?php foreach ($errores as $error): ?>
              <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="create-form-container">
        <form method="POST" action="">
          <div class="form-group-create">
            <label>Nombre:</label>
            <input
              type="text"
              id="nombre"
              name="nombre"
              value="<?php echo htmlspecialchars($dueno->nombre); ?>"
              required
              pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
              title="Solo letras y espacios permitidos"
            />
          </div>

          <div class="form-group-create">
            <label>Teléfono:</label>
            <input
              type="text"
              id="telefono"
              name="telefono"
              value="<?php echo htmlspecialchars($dueno->telefono); ?>"
              pattern="[0-9\-]+"
              title="Solo números y guiones permitidos"
            />
          </div>

          <div class="form-group-create">
            <label>Correo electrónico:</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?php echo htmlspecialchars($dueno->email); ?>"
            />
          </div>

          <div class="form-group-create">
            <label>Dirección:</label>
            <input
              type="text"
              id="direccion"
              name="direccion"
              value="<?php echo htmlspecialchars($dueno->direccion); ?>"
            />
          </div>

          <div class="create-buttons">
            <button type="submit" class="btn-form-create">
              Guardar cambios
            </button>
            <button
              type="button"
              class="btn-form-cancel"
              onclick="window.location.href='duenos.php'"
            >
              Cancelar
            </button>
          </div>
        </form>
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