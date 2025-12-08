<?php
// Incluir configuración y modelos
include_once '../config/database.php';
include_once '../models/Paciente.php';
include_once '../controllers/PacienteController.php';

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear controlador
$pacienteController = new PacienteController($db);

// Obtener ID del paciente
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: paciente.php");
    exit();
}

// Obtener datos del paciente
$paciente = $pacienteController->obtenerPaciente($id);

if (!$paciente) {
    header("Location: paciente.php?error=not_found");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalles - VETEICA CLINIC</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../public/css/styles.css" />
  </head>
  <body>
    <header class="main-header">
      <nav class="main-nav">
        <a href="inicio.html" class="nav-logo">
          <img
            src="../public/css/img/logoVetica.png"
            alt="Veteica Clinic Logo"
          />
        </a>
        <ul class="nav-links">
          <li><a href="inicio.php">INICIO</a></li>
          <li><a href="paciente.php" class="link-active">PACIENTE</a></li>
          <li><a href="duenos.php">DUEÑOS</a></li>
          <li><a href="citas.php">CITAS</a></li>
        </ul>
        <div class="nav-profile"><span>N</span></div>

        <div class="profile">
          <h3>Perfil del administrador</h3>
          <div class="profile-header">
            <div class="profile-avatar"><span>N</span></div>
            <strong>ejemplo</strong>
            <p>ejemplo@gmail.com</p>
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
            <a href="index.html">Cerrar sesión</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container">
      <div class="details-container">
        <h1>Detalle de Mascota: <span id="pet-name-display"><?php echo htmlspecialchars($paciente->nombre); ?></span></h1>

        <img
          src="<?php echo $paciente->imagen ? htmlspecialchars($paciente->imagen) : '../public/css/img/perroInicio.png'; ?>"
          alt="Foto de <?php echo htmlspecialchars($paciente->nombre); ?>"
          class="pet-detail-image"
        />

        <table class="details-table">
          <tr>
            <th>Especie</th>
            <td><?php echo htmlspecialchars(ucfirst($paciente->especie)); ?></td>
          </tr>
          <tr>
            <th>Raza</th>
            <td><?php echo htmlspecialchars($paciente->raza ?? 'No especificado'); ?></td>
          </tr>
          <tr>
            <th>Edad</th>
            <td><?php echo htmlspecialchars($paciente->edad ?? '0'); ?> meses</td>
          </tr>
          <tr>
            <th>Sexo</th>
            <td><?php echo htmlspecialchars($paciente->sexo ?? 'No especificado'); ?></td>
          </tr>
        </table>

        <h2 class="history-title">Historial Clinico</h2>

        <table class="history-table">
          <thead>
            <tr>
              <th>Consultas</th>
              <th>Fechas</th>
              <th>Diagnósticos</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>22/10/2025</td>
              <td>Ligero Problema en oido</td>
            </tr>
            <tr>
              <td>2</td>
              <td>23/10/2025</td>
              <td>Aun con ligero problema en oido</td>
            </tr>
            <tr>
              <td>3</td>
              <td>24/10/2025</td>
              <td>Infección de oido.</td>
            </tr>
          </tbody>
        </table>

        <div class="info-grid">
          <div>
            <div class="info-block-header">Dueño Asociado</div>
            <div class="info-block-content"><?php echo htmlspecialchars($paciente->nombre_dueno ?? 'No asignado'); ?></div>
          </div>
          <div>
            <div class="info-block-header">Diagnósticos Actual</div>
            <div class="info-block-content"><?php echo htmlspecialchars($paciente->diagnostico ?? 'No especificado'); ?></div>
          </div>
        </div>

        <div class="pdf-buttons">
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