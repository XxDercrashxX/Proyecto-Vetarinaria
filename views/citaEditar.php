<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Cita.php';
include_once '../models/Paciente.php';
include_once '../models/Dueno.php';
include_once '../controllers/CitaController.php';

$database = new Database();
$db = $database->getConnection();
$citaController = new CitaController($db);

// Obtener pacientes y dueños para los selects
$paciente = new Paciente($db);
$stmtPacientes = $paciente->leer();

$dueno = new Dueno($db);
$stmtDuenos = $dueno->leer();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: citas.php");
    exit();
}

$cita = $citaController->obtenerCita($id);

if (!$cita) {
    header("Location: citas.php?error=not_found");
    exit();
}

$errores = [];

if ($_POST) {
    $datos = [
        'fecha' => $_POST['fecha'] ?? '',
        'hora' => $_POST['hora'] ?? '',
        'id_paciente' => $_POST['id_paciente'] ?? '',
        'id_dueno' => $_POST['id_dueno'] ?? '',
        'motivo' => $_POST['motivo'] ?? '',
        'diagnostico' => $_POST['diagnostico'] ?? '',
        'estado' => $_POST['estado'] ?? 'programada'
    ];

    $resultado = $citaController->actualizarCita($id, $datos);
    
    if ($resultado['success']) {
        header("Location: citas.php?success=update");
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
    <title>Editar Cita - VETEICA CLINIC</title>
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
      <h2 class="page-title">Editar Cita</h2>

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
            <label>Paciente:</label>
            <select name="id_paciente" required>
              <option value="">Seleccionar paciente</option>
              <?php while ($pacienteRow = $stmtPacientes->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $pacienteRow['id']; ?>" 
                        <?php echo ($cita->id_paciente == $pacienteRow['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($pacienteRow['nombre'] . ' - ' . $pacienteRow['especie']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group-create">
            <label>Dueño:</label>
            <select name="id_dueno" required>
              <option value="">Seleccionar dueño</option>
              <?php 
              $stmtDuenos = $dueno->leer(); // Re-leer dueños
              while ($duenoRow = $stmtDuenos->fetch(PDO::FETCH_ASSOC)): 
              ?>
                <option value="<?php echo $duenoRow['id']; ?>" 
                        <?php echo ($cita->id_dueno == $duenoRow['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($duenoRow['nombre']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="form-group-create">
            <label>Fecha:</label>
            <input type="date" name="fecha" value="<?php echo htmlspecialchars($cita->fecha); ?>" required 
                   min="<?php echo date('Y-m-d'); ?>" />
          </div>

          <div class="form-group-create">
            <label>Hora:</label>
            <input type="time" name="hora" value="<?php echo htmlspecialchars($cita->hora); ?>" required />
          </div>

          <div class="form-group-create">
            <label>Motivo:</label>
            <input type="text" name="motivo" value="<?php echo htmlspecialchars($cita->motivo); ?>" required />
          </div>

          <div class="form-group-create">
            <label>Diagnóstico:</label>
            <input type="text" name="diagnostico" value="<?php echo htmlspecialchars($cita->diagnostico); ?>" />
          </div>

          <div class="form-group-create">
            <label>Estado:</label>
            <select name="estado">
              <option value="programada" <?php echo ($cita->estado == 'programada') ? 'selected' : ''; ?>>Programada</option>
              <option value="completada" <?php echo ($cita->estado == 'completada') ? 'selected' : ''; ?>>Completada</option>
              <option value="cancelada" <?php echo ($cita->estado == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
            </select>
          </div>

          <div class="create-buttons">
            <button type="submit" class="btn-form-create">
              Guardar cambios
            </button>
            <button
              type="button"
              class="btn-form-cancel"
              onclick="window.location.href='citas.php'"
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