<?php
// Incluir configuración y modelos
include_once '../config/database.php';
include_once '../models/Paciente.php';
include_once '../controllers/PacienteController.php';
include_once '../models/Dueno.php';

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear controladores
$pacienteController = new PacienteController($db);

// Obtener lista de dueños
$dueno = new Dueno($db);
$stmtDuenos = $dueno->leer();

// Obtener ID del paciente
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: paciente.php");
    exit();
}

// Obtener datos actuales del paciente
$paciente = $pacienteController->obtenerPaciente($id);

if (!$paciente) {
    header("Location: paciente.php?error=not_found");
    exit();
}

// Procesar formulario de edición
$errores = [];

if ($_POST) {
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'especie' => $_POST['especie'] ?? '',
        'raza' => $_POST['raza'] ?? '',
        'edad' => $_POST['edad'] ?? '',
        'sexo' => $_POST['sexo'] ?? '',
        'diagnostico' => $_POST['diagnostico'] ?? '',
        'hospitalizado' => $_POST['hospitalizado'] ?? 'no',
        'id_dueno' => $_POST['id_dueno'] ?? null,
        'imagen' => $paciente->imagen // Mantener imagen actual por ahora
    ];

    $resultado = $pacienteController->actualizarPaciente($id, $datos);
    
    if ($resultado['success']) {
        header("Location: paciente.php?success=update");
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
    <title>Editar Paciente - VETEICA CLINIC</title>
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
      <h2 class="page-title">
        Editar Paciente: <span id="edit-title-name"><?php echo htmlspecialchars($paciente->nombre); ?></span>
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
          <div style="text-align: center; margin-bottom: 2rem">
            <img
              id="current-image"
              src="<?php echo $paciente->imagen ? htmlspecialchars($paciente->imagen) : '../public/css/img/perroInicio.png'; ?>"
              alt="Foto actual"
              style="
                width: 200px;
                height: 150px;
                object-fit: cover;
                border-radius: 10px;
                border: 1px solid #ddd;
              "
            />
          </div>

          <div class="form-group-create">
            <label>Nombre:</label>
            <input
              type="text"
              id="nombre"
              name="nombre"
              value="<?php echo htmlspecialchars($paciente->nombre); ?>"
              required
              pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
              title="Solo letras y espacios permitidos"
            />
          </div>

          <div class="form-group-create">
            <label>Especie:</label>
            <select id="especie" name="especie" required>
              <option value="perro" <?php echo ($paciente->especie == 'perro') ? 'selected' : ''; ?>>Perro</option>
              <option value="gato" <?php echo ($paciente->especie == 'gato') ? 'selected' : ''; ?>>Gato</option>
              <option value="otro" <?php echo ($paciente->especie == 'otro') ? 'selected' : ''; ?>>Otro</option>
            </select>
          </div>

          <div class="form-group-create">
            <label>Raza:</label>
            <input
              type="text"
              id="raza"
              name="raza"
              value="<?php echo htmlspecialchars($paciente->raza ?? ''); ?>"
              pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*"
              title="Solo letras y espacios permitidos"
            />
          </div>

          <div class="form-group-create">
            <label>Edad:</label>
            <input 
              type="number" 
              id="edad" 
              name="edad" 
              value="<?php echo htmlspecialchars($paciente->edad ?? ''); ?>" 
              min="0" 
              max="50" 
              step="1" 
            />
          </div>

          <div class="form-group-create">
            <label>Sexo:</label>
            <input
              type="text"
              id="sexo"
              name="sexo"
              value="<?php echo htmlspecialchars($paciente->sexo ?? ''); ?>"
              pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*"
              title="Solo letras permitidas"
            />
          </div>

          <div class="form-group-create">
            <label>Diagnóstico Actual:</label>
            <input
              type="text"
              id="diagnostico"
              name="diagnostico"
              value="<?php echo htmlspecialchars($paciente->diagnostico ?? ''); ?>"
            />
          </div>

          <div class="form-group-create">
            <label>Hospitalizado:</label>
            <select id="hospitalizado" name="hospitalizado">
              <option value="no" <?php echo ($paciente->hospitalizado == 'no') ? 'selected' : ''; ?>>No</option>
              <option value="si" <?php echo ($paciente->hospitalizado == 'si') ? 'selected' : ''; ?>>Sí</option>
            </select>
          </div>

          <div class="form-group-create">
            <label>Dueños Asociados:</label>
            <select name="id_dueno">
              <option value="">Seleccionar dueño</option>
              <?php 
              $stmtDuenos = $dueno->leer(); // Re-leer dueños para el select
              while ($duenoRow = $stmtDuenos->fetch(PDO::FETCH_ASSOC)): 
              ?>
                <option value="<?php echo $duenoRow['id']; ?>" 
                        <?php echo ($paciente->id_dueno == $duenoRow['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($duenoRow['nombre']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="create-buttons">
            <button type="submit" class="btn-form-create">
              Guardar cambios
            </button>
            <button
              type="button"
              class="btn-form-cancel"
              onclick="window.location.href='paciente.php'"
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