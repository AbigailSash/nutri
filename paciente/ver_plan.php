<?php
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Obtener datos del paciente logueado
$stmt = $conn->prepare("
    SELECT p.id_paciente, per.nombre, per.apellido
    FROM paciente p
    JOIN usuario u ON p.id_usuario = u.id_usuario
    JOIN persona per ON u.id_persona = per.id_persona
    WHERE u.id_usuario = ?
");
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();

if (!$paciente) {
    echo "<p style='padding:2rem;'>❌ No se pudo identificar al paciente.</p>";
    exit;
}

$id_paciente = $paciente['id_paciente'];
$nombreCompleto = $paciente['nombre'] . ' ' . $paciente['apellido'];

// Obtener planes nutricionales
$stmtPlanes = $conn->prepare("
    SELECT fecha_entrega, archivo_pdf
    FROM plan_nutricional
    WHERE id_paciente = ?
    ORDER BY fecha_entrega DESC
");
$stmtPlanes->bind_param("i", $id_paciente);
$stmtPlanes->execute();
$planes = $stmtPlanes->get_result();
?>

<div class="main-content">
  <div class="contenido-central">
    <h2 class="titulo-seccion">Mis Planes Alimentarios</h2>

    <?php if ($planes->num_rows === 0): ?>
      <p>No hay planes cargados aún.</p>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Fecha de entrega</th>
            <th>Archivo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($plan = $planes->fetch_assoc()):
            $ruta = '../' . $plan['archivo_pdf'];
            $nombreArchivo = basename($plan['archivo_pdf']);
            $esPDF = strtolower(pathinfo($ruta, PATHINFO_EXTENSION)) === 'pdf';
        ?>
          <tr>
            <td><?= date('d/m/Y', strtotime($plan['fecha_entrega'])) ?></td>
            <td><?= htmlspecialchars($nombreArchivo) ?></td>
            <td>
              <?php if (file_exists($ruta) && $esPDF): ?>
                <a href="<?= $ruta ?>" target="_blank" class="btn btn-violeta btn-sm">📄 Ver / Descargar</a>
              <?php elseif (!file_exists($ruta)): ?>
                <span class="text-danger" style="color: #c0392b;">❌ Archivo no encontrado</span>
              <?php else: ?>
                <span class="text-danger" style="color: #c0392b;">❌ Formato no permitido</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
