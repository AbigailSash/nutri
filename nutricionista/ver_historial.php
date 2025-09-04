<?php
session_start();
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header('Location: ../auth/login.php');
    exit;
}

/*--------------------------------------------------
  1. Validar id_paciente
--------------------------------------------------*/
$id_paciente = $_GET['id_paciente'] ?? null;
if (!$id_paciente || !ctype_digit($id_paciente)) {
    echo "<div class='alert alert-danger text-center mt-4'>ID de paciente no válido.</div>";
    exit;
}

/*--------------------------------------------------
  2. Nombre del paciente
--------------------------------------------------*/
$stmtNom = $conn->prepare("
    SELECT p.nombre, p.apellido
    FROM   paciente pa
    JOIN   usuario  u ON u.id_usuario  = pa.id_usuario
    JOIN   persona  p ON p.id_persona  = u.id_persona
    WHERE  pa.id_paciente = ?
");
$stmtNom->bind_param('i', $id_paciente);
$stmtNom->execute();
$paciente = $stmtNom->get_result()->fetch_assoc();
if (!$paciente) {
    echo "<div class='alert alert-danger text-center mt-4'>Paciente no encontrado.</div>";
    exit;
}

/*--------------------------------------------------
  3. Motivo/antecedentes de la PRIMERA consulta inicial
--------------------------------------------------*/
$stmtIni = $conn->prepare("
    SELECT motivo, antecedentes, medicacion
    FROM   consulta_inicial ci
    JOIN   consulta        c  ON c.id_consulta = ci.id_consulta
    WHERE  c.id_paciente = ?
    ORDER  BY c.fecha ASC
    LIMIT 1
");
$stmtIni->bind_param('i', $id_paciente);
$stmtIni->execute();
$datosInicial = $stmtIni->get_result()->fetch_assoc() ?: [
    'motivo'        => null,
    'antecedentes'  => null,
    'medicacion'    => null
];

/*--------------------------------------------------
  4. Historial de todas las consultas
--------------------------------------------------*/
$stmt = $conn->prepare("
    SELECT c.id_consulta,
           c.fecha,
           c.diagnostico AS diag_seguimiento,
           ci.motivo AS motivo_inicial
    FROM   consulta c
    LEFT   JOIN consulta_inicial ci ON ci.id_consulta = c.id_consulta
    WHERE  c.id_paciente = ?
    ORDER  BY c.fecha DESC
");
$stmt->bind_param('i', $id_paciente);
$stmt->execute();
$res = $stmt->get_result();
?>
<!-- ----------  Render  ---------- -->
<div class="main-content">
  <div class="contenido-central">
    <h2 class="titulo-seccion">Historial de Consultas</h2>
    <p><strong>Paciente:</strong> <?= htmlspecialchars($paciente['nombre'].' '.$paciente['apellido']) ?></p>

    <?php if ($res->num_rows): ?>
      <table class="table table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th style="width:160px">Fecha</th>
            <th style="width:45%">Motivo / Observación</th>
            <th style="width:120px">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
          <?php
            /* ---------- Fallback ---------- */
            $motivo = $row['diag_seguimiento']
                      ?: $row['motivo_inicial']
                      ?: $datosInicial['motivo']
                      ?: '—';
          ?>
          <tr>
            <td><?= date('d/m/Y H:i', strtotime($row['fecha'])) ?></td>
            <td><?= htmlspecialchars($motivo) ?></td>
            <td class="text-center">
              <a href="ver_calculo_nutricional.php?id_consulta=<?= $row['id_consulta'] ?>"
                 class="btn btn-outline-info btn-sm">Ver Resultados</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-warning text-center mt-4">
        Este paciente aún no tiene consultas registradas.
      </div>
    <?php endif; ?>

    <a href="index.php?seccion=historial" class="btn btn-secondary mt-3">← Volver</a>
  </div>
</div>
