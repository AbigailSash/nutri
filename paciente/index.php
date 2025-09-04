<?php
session_start();
require_once '../bd/conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'paciente') {
    header('Location: ../auth/login.php');
    exit;
}

$seccion_activa = $_GET['seccion'] ?? '';

// Cargar elementos visuales comunes
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
  <div class="contenido-central">
    <?php
    switch ($seccion_activa) {
      case 'ver_plan':
        require_once 'ver_plan.php';
        break;
      case 'historial':
        require_once 'historial.php';
        break;
      case 'graficos':
        require_once 'seguimiento.php';
        break;

      default:
        // Bienvenida con mismo estilo que el nutricionista
        $id_usuario = $_SESSION['id_usuario'];

        $stmt = $conn->prepare("SELECT p.nombre, p.apellido, p.dni, p.correo, p.telefono 
                                FROM usuario u 
                                JOIN persona p ON u.id_persona = p.id_persona 
                                WHERE u.id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $paciente = $resultado->fetch_assoc();
    ?>
        <div class="d-flex justify-content-center mt-5">
          <div class="card shadow-sm p-4" 
               style="background-color: var(--lila-claro); border-radius: 16px; max-width: 700px; width: 100%;">
            <h3 class="mb-3">Bienvenido/a, <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']) ?></h3>

            <p class="mb-2"><strong>DNI:</strong> 
              <?= $paciente['dni'] ?? '<em>No disponible</em>' ?>
            </p>

            <p class="mb-2"><strong>Correo:</strong> 
              <?= $paciente['correo'] ?? '<em>No disponible</em>' ?>
            </p>

            <p class="mb-2"><strong>Teléfono:</strong> 
              <?= $paciente['telefono'] ?? '<em>No disponible</em>' ?>
            </p>

            <hr>
            <p class="text-muted">“Seguimos avanzando hacia tus objetivos. ¡Buen trabajo!”</p>
          </div>
        </div>
    <?php
        break;
    }
    ?>
  </div>
</div>
