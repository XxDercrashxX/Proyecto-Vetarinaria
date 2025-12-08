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

// Obtener lista de pacientes
$pacientes = $pacienteController->listarPacientes();

// Procesar eliminación si se solicita
if ($_POST && isset($_POST['eliminar_id'])) {
    $resultado = $pacienteController->eliminarPaciente($_POST['eliminar_id']);
    if ($resultado['success']) {
        header("Location: paciente.php?success=delete");
        exit();
    } else {
        $error = $resultado['errors'][0];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pacientes - VETEICA CLINIC</title>
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
            <a href="index.php">Cerrar sesión</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="container">
      <div class="patient-panel">
        <h2>Panel de paciente</h2>
        <div class="patient-actions">
          <a href="pacienteCrear.php" class="btn-create"
            >Crear paciente <i class="fas fa-plus"></i
          ></a>
          <div class="search-bar">
            <i class="fas fa-search"></i
            ><input type="text" placeholder="Buscar por ID" id="search-input" />
          </div>
        </div>
      </div>

      <?php if (isset($error)): ?>
        <div class="alert error">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert success">
          <?php 
          if ($_GET['success'] == 'create') echo "Paciente creado correctamente";
          if ($_GET['success'] == 'update') echo "Paciente actualizado correctamente";
          if ($_GET['success'] == 'delete') echo "Paciente eliminado correctamente";
          ?>
        </div>
      <?php endif; ?>

      <div class="table-container">
        <h3>Consultar registro</h3>
        <table class="patient-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Especie</th>
              <th>Dueño asociado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tabla-pacientes-body">
            <?php while ($row = $pacientes->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id']); ?></td>
              <td><?php echo htmlspecialchars($row['nombre']); ?></td>
              <td><?php echo htmlspecialchars($row['especie']); ?></td>
              <td><?php echo htmlspecialchars($row['nombre_dueno'] ?? 'No asignado'); ?></td>
              <td>
                <a
                  href="pacienteDetalles.php?id=<?php echo $row['id']; ?>"
                  class="btn-action btn-details"
                  >Detalles</a
                >
                <a href="pacienteEditar.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit"
                  >Editar</a
                >

                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este paciente?')">
                  <input type="hidden" name="eliminar_id" value="<?php echo $row['id']; ?>">
                  <button type="submit" class="btn-action btn-delete" style="border: none; background: none; cursor: pointer;">Eliminar</button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
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

    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tabla-pacientes-body tr');
        
        rows.forEach(row => {            if (row.cells.length > 1) {
                const id = row.cells[0].textContent.toLowerCase();
                const nombre = row.cells[1].textContent.toLowerCase();
                const especie = row.cells[2].textContent.toLowerCase();

                const dueno = row.cells[3].textContent.toLowerCase();
                
                if (id.includes(searchTerm) || nombre.includes(searchTerm) || 
                    especie.includes(searchTerm) || dueno.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
</script>
  </body>
</html>