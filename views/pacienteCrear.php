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

// Obtener lista de dueños para el select
$dueno = new Dueno($db);
$stmtDuenos = $dueno->leer();

// Procesar formulario
$errores = [];
$success = false;

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
        'imagen' => '' // Manejo de imágenes pendiente
    ];

    $resultado = $pacienteController->crearPaciente($datos);
    
    if ($resultado['success']) {
        header("Location: paciente.php?success=create");
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
    <title>Crear Paciente - VETEICA CLINIC</title>
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
      <h2 class="page-title">Crear Paciente</h2>

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
          <div class="image-upload-area">
            <label
              for="file-upload"
              class="custom-file-upload"
              id="upload-label"
            >
              Agregar imagen
            </label>
            <img
              id="image-preview"
              src="#"
              alt="Vista previa"
              style="display: none"
            />
            <input
              id="file-upload"
              type="file"
              accept="image/*"
              onchange="previewImage(event)"
            />
          </div>

          <div class="form-group-create">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required 
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo letras y espacios permitidos" />
          </div>

          <div class="form-group-create">
            <label>Especie:</label>
            <select name="especie" required>
              <option value="perro" <?php echo (($_POST['especie'] ?? '') == 'perro') ? 'selected' : ''; ?>>Perro</option>
              <option value="gato" <?php echo (($_POST['especie'] ?? '') == 'gato') ? 'selected' : ''; ?>>Gato</option>
              <option value="otro" <?php echo (($_POST['especie'] ?? '') == 'otro') ? 'selected' : ''; ?>>Otro</option>
            </select>
          </div>

          <div class="form-group-create">
            <label>Raza:</label>
            <input type="text" name="raza" value="<?php echo htmlspecialchars($_POST['raza'] ?? ''); ?>" 
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*" title="Solo letras y espacios permitidos" />
          </div>

          <div class="form-group-create">
            <label>Edad:</label>
            <input type="number" name="edad" value="<?php echo htmlspecialchars($_POST['edad'] ?? ''); ?>" 
                   min="0" max="50" step="1" />
          </div>

          <div class="form-group-create">
            <label>Sexo:</label>
            <input type="text" name="sexo" value="<?php echo htmlspecialchars($_POST['sexo'] ?? ''); ?>" 
                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*" title="Solo letras permitidas" />
          </div>

          <div class="form-group-create">
            <label>Diagnóstico Actual:</label>
            <input type="text" name="diagnostico" value="<?php echo htmlspecialchars($_POST['diagnostico'] ?? ''); ?>" />
          </div>

          <div class="form-group-create">
            <label>Hospitalizado:</label>
            <select name="hospitalizado">
              <option value="no" <?php echo (($_POST['hospitalizado'] ?? 'no') == 'no') ? 'selected' : ''; ?>>No</option>
              <option value="si" <?php echo (($_POST['hospitalizado'] ?? 'no') == 'si') ? 'selected' : ''; ?>>Sí</option>
            </select>
          </div>

          <div class="form-group-create">
            <label>Dueño Asociado:</label>
            <select name="id_dueno">
              <option value="">Seleccionar dueño</option>
              <?php while ($duenoRow = $stmtDuenos->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo $duenoRow['id']; ?>" 
                        <?php echo (($_POST['id_dueno'] ?? '') == $duenoRow['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($duenoRow['nombre']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="create-buttons">
            <button type="submit" class="btn-form-create">Crear</button>
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

      function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
          const output = document.getElementById("image-preview");
          const label = document.getElementById("upload-label");
          output.src = reader.result;
          output.style.display = "block";
          label.style.display = "none";
        };
        reader.readAsDataURL(event.target.files[0]);
      }
    </script>
  </body>
</html>