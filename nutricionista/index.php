<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header("Location: ../auth/login.php");
    exit;
}

// Conexión a base de datos
require_once '../bd/conexion.php';

// Header y sidebar
include 'includes/header.php';
include 'includes/sidebar.php';

// Obtener datos actualizados del nutricionista desde la base
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT p.nombre, p.apellido, p.correo, p.telefono, n.matricula, n.especializacion
        FROM usuario u
        JOIN persona p ON u.id_persona = p.id_persona
        JOIN nutricionista n ON u.id_usuario = n.id_usuario
        WHERE u.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$nutri = $resultado->fetch_assoc();
?>

<!-- ✅ Contenido principal -->
<div class="main-content">
<?php
$seccion = $_GET['seccion'] ?? 'inicio';

switch ($seccion) {
    case 'pacientes':
        include 'pacientes.php';
        break;
    case 'consulta':
        include 'nueva_consulta.php';
        break;
    case 'historial':
        include 'historial.php';
        break;
    case 'plan':
        include 'plan.php';
        break;
    case 'seguimiento':
        include 'seguimiento.php';
        break;
    default:
        ?>
        <!-- Vista de bienvenida dinámica -->
        <div class="d-flex justify-content-center mt-5">
            <div class="card shadow-sm p-4" style="background-color: var(--lila-claro); border-radius: 16px; max-width: 700px;">
                <h3 class="mb-3">Bienvenido/a, <?php echo htmlspecialchars($nutri['nombre'] . ' ' . $nutri['apellido']); ?></h3>

                <p class="mb-2"><strong>Matrícula profesional:</strong> 
                    <?php echo $nutri['matricula'] ?? '<em>No registrada</em>'; ?>
                </p>

                <p class="mb-2"><strong>Especialización:</strong> 
                    <?php echo $nutri['especializacion'] ?? '<em>General</em>'; ?>
                </p>

                <p class="mb-2"><strong>Correo:</strong> 
                    <?php echo $nutri['correo'] ?? '<em>No disponible</em>'; ?>
                </p>

                <p class="mb-2"><strong>Teléfono:</strong> 
                    <?php echo $nutri['telefono'] ?? '<em>No disponible</em>'; ?>
                </p>

                <hr>
                <p class="text-muted">Gracias por cuidar la salud nutricional de tus pacientes 🌿</p>
            </div>
        </div>
        <?php
        break;
}
?>
</div>
