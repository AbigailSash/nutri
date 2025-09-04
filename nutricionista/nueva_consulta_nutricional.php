<?php
session_start();
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header("Location: ../auth/login.php");
    exit;
}

$id_paciente = $_GET['id_paciente'] ?? null;
if (!$id_paciente) {
    header("Location: index.php?seccion=consulta&error=sinPaciente");
    exit;
}

// ✅ Verificar si tiene consulta inicial
$verifica = $conn->prepare("
    SELECT 1
    FROM consulta_inicial ci
    JOIN consulta c ON ci.id_consulta = c.id_consulta
    WHERE c.id_paciente = ?
");
$verifica->bind_param("i", $id_paciente);
$verifica->execute();
$resCheck = $verifica->get_result();

if ($resCheck->num_rows === 0) {
    echo "<div class='alert alert-warning text-center mt-4'>⚠️ Este paciente no cuenta con una consulta inicial aún.</div>";
    exit;
}

// ✅ Obtener datos del paciente
$stmt = $conn->prepare("
    SELECT p.nombre, p.apellido, p.dni 
    FROM paciente pa 
    JOIN usuario u ON pa.id_usuario = u.id_usuario 
    JOIN persona p ON u.id_persona = p.id_persona 
    WHERE pa.id_paciente = ?
");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$res = $stmt->get_result();
$paciente = $res->fetch_assoc();

if (!$paciente) {
    echo "<div class='alert alert-danger text-center'>❌ Paciente no encontrado.</div>";
    exit;
}
?>

<div class="main-content">
  <div class="contenido-central">
    <h2 class="titulo-seccion">Seguimiento Nutricional</h2>
    <p><strong>Fecha:</strong> <?= date('Y-m-d') ?></p>
    <p><strong>Paciente:</strong> <?= htmlspecialchars($paciente['nombre']) . ' ' . htmlspecialchars($paciente['apellido']) ?> (DNI: <?= htmlspecialchars($paciente['dni']) ?>)</p>

    <form action="guardarConsultaNutricional.php" method="POST">
      <input type="hidden" name="id_paciente" value="<?= $id_paciente ?>">

      <div class="row">
        <div class="col-md-6">
          <label>Peso (kg)</label>
          <input type="number" step="0.01" name="peso" class="form-control" required>

          <label>Altura (m)</label>
          <input type="number" step="0.01" name="altura" class="form-control" required>

          <label>% Grasa corporal</label>
          <input type="number" step="0.1" name="porcentaje_grasa" class="form-control">

          <label>% Masa muscular</label>
          <input type="number" step="0.1" name="porcentaje_muscular" class="form-control">

          <label>Cintura (cm)</label>
          <input type="number" step="0.1" name="cintura" class="form-control">

          <label>Cadera (cm)</label>
          <input type="number" step="0.1" name="cadera" class="form-control">
        </div>

        <div class="col-md-6">
          <label>Pliegue Tricipital (mm)</label>
          <input type="number" step="0.1" name="pliegue_tricipital" class="form-control">

          <label>Pliegue Subescapular (mm)</label>
          <input type="number" step="0.1" name="pliegue_subescapular" class="form-control">

          <label>Pliegue Suprailiaco (mm)</label>
          <input type="number" step="0.1" name="pliegue_suprailiaco" class="form-control">

          <label>Observaciones / Diagnóstico</label>
          <textarea name="observaciones" class="form-control" rows="5"></textarea>
        </div>
      </div>

      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Guardar Seguimiento</button>
        <a href="index.php?seccion=consulta" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
