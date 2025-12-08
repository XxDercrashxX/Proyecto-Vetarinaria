<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Cita.php';
include_once '../controllers/CitaController.php';

$database = new Database();
$db = $database->getConnection();
$citaController = new CitaController($db);

$citas = $citaController->listarCitas();

if ($_POST && isset($_POST['eliminar_id'])) {
    $resultado = $citaController->eliminarCita($_POST['eliminar_id']);
    if ($resultado['success']) {
        header("Location: citas.php?success=delete");
        exit();
    } else {
        $error = $resultado['errors'][0];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Citas - VETEICA CLINIC</title>
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
        <li><a href="paciente.php"> PACIENTE</a></li>
        <li><a href="duenos.php">  DUEÑOS</a> </li>
        <li><a href="citas.php" class="link-active">CITAS</a></li>
    </ul>
    <div class="nav-profile">
        <span><?php echo substr($_SESSION['user_nombre'], 0, 1); ?></span> 
    </div>
    <div class="profile">
        <h3>Perfil del administrador</h3>
            <div class="profile-header">
            <div class="profile-avatar">
                <span><?php echo substr($_SESSION['user_nombre'], 0, 1); ?></span>
            </div>
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
            <a href="../index.php">Cerrar sesión</a>
    </div>
        </div>
    </nav>
</header>
<main class="container">
<div class="patient-panel">
    <h2>Panel de citas</h2>
    
    <div class="patient-actions">
        <a href="citasCrear.php" class="btn-create">
            Crear Cita
            <i class="fas fa-plus"></i>
        </a>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar por ID" id="search-input">
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
        if ($_GET['success'] == 'create') echo "Cita creada correctamente";
        if ($_GET['success'] == 'update') echo "Cita actualizada correctamente";
        if ($_GET['success'] == 'delete') echo "Cita eliminada correctamente";
        ?>
    </div>
<?php endif; ?>

<div class="table-container">
<h3>Consultar Agendas</h3>
    <table class="patient-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Dueño</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    <tbody id="tabla-citas-body">
        <?php while ($row = $citas->fetch(PDO::FETCH_ASSOC)): ?>
        <tr> 
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
            <td><?php echo date('H:i', strtotime($row['hora'])); ?></td>
            <td><?php echo htmlspecialchars($row['nombre_paciente']); ?></td>
            <td><?php echo htmlspecialchars($row['nombre_dueno']); ?></td>
            <td>
                <span class="estado-<?php echo $row['estado']; ?>">
                    <?php echo ucfirst($row['estado']); ?>
                </span>
            </td>
            <td>
                <a href="citaEditar.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Editar</a>
                <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?')">
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

<footer class="main-footer">
     © 2025 Veterinaria Amor y lealtad
</footer>

<script>
    const profileIcon = document.querySelector('.nav-profile');
    const profileCard = document.querySelector('.profile');

    profileIcon.addEventListener('click', (e) => {
        e.stopPropagation(); 
        profileCard.classList.toggle('active');
    });

    window.addEventListener('click', (e) => {
        if (!profileCard.contains(e.target)) {
            profileCard.classList.remove('active');
        }
    });

    // Búsqueda en tiempo real
    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tabla-citas-body tr');
        
        rows.forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const fecha = row.cells[1].textContent.toLowerCase();
            const hora = row.cells[2].textContent.toLowerCase();
            const paciente = row.cells[3].textContent.toLowerCase();
            const dueno = row.cells[4].textContent.toLowerCase();
            
            if (id.includes(searchTerm) || fecha.includes(searchTerm) || 
                hora.includes(searchTerm) || paciente.includes(searchTerm) || 
                dueno.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>