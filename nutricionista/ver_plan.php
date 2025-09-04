<?php
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_GET['id_paciente'])) {
    echo "<p style='padding:2rem;'>❌ No se proporcionó ID de paciente.</p>";
    exit;
}

$id_paciente = intval($_GET['id_paciente']);

// Obtener nombre del paciente
$stmtNombre = $conn->prepare("
    SELECT per.nombre, per.apellido
    FROM paciente p
    JOIN usuario u ON p.id_usuario = u.id_usuario
    JOIN persona per ON u.id_persona = per.id_persona
    WHERE p.id_paciente = ?
");
$stmtNombre->bind_param("i", $id_paciente);
$stmtNombre->execute();
$paciente = $stmtNombre->get_result()->fetch_assoc();

if (!$paciente) {
    echo "<p style='padding:2rem;'>❌ Paciente no encontrado.</p>";
    exit;
}

// Obtener planes nutricionales del paciente
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
    <h2 class="titulo-seccion">Planes Alimentarios de <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']) ?></h2>

    <?php if ($planes->num_rows === 0): ?>
      <p>No hay planes cargados para este paciente.</p>
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
                <span class="text-danger">❌ Archivo no encontrado</span>
              <?php else: ?>
                <span class="text-danger">❌ Formato no permitido</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
