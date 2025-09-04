<?php
session_start();
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id_paciente'])) {
    echo "<div class='alert alert-danger text-center'>Paciente no especificado.</div>";
    exit;
}

$id_paciente = intval($_GET['id_paciente']);

// Verificar si ya existe una consulta inicial
$existe_stmt = $conn->prepare("SELECT ci.id_inicial FROM consulta_inicial ci 
                                JOIN consulta c ON ci.id_consulta = c.id_consulta
                                WHERE c.id_paciente = ? LIMIT 1");
$existe_stmt->bind_param("i", $id_paciente);
$existe_stmt->execute();
$existe_stmt->store_result();
if ($existe_stmt->num_rows > 0) {
    echo "<div class='alert alert-warning text-center mt-5'>⚠️ Este paciente ya tiene una consulta inicial registrada. No se puede volver a registrar.</div>";
    echo "<div class='text-center'><a href='nueva_consulta.php' class='btn btn-secondary mt-3'>Volver</a></div>";
    exit;
}

// Obtener datos del paciente
$stmt = $conn->prepare("SELECT p.nombre, p.apellido, p.dni, p.tipoSexo, p.fecha_nacimiento
                        FROM paciente pa
                        JOIN usuario u ON pa.id_usuario = u.id_usuario
                        JOIN persona p ON u.id_persona = p.id_persona
                        WHERE pa.id_paciente = ?");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$resultado = $stmt->get_result();
$paciente = $resultado->fetch_assoc();

if (!$paciente) {
    echo "<div class='alert alert-danger text-center'>Paciente no encontrado.</div>";
    exit;
}

// Calcular edad
$fecha_nac = new DateTime($paciente['fecha_nacimiento']);
$hoy = new DateTime();
$edad = $fecha_nac->diff($hoy)->y;
$sexo = ucfirst($paciente['tipoSexo']);

$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);
?>

<div class="main-content">
  <div class="contenido-central">
    <h2 class="titulo-seccion">Registrar Consulta Inicial</h2>
    <?= $mensaje ?>

    <p><strong>Fecha:</strong> <?= date('Y-m-d') ?></p>
    <p><strong>Paciente:</strong> <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']) ?> (DNI: <?= $paciente['dni'] ?>) - Edad: <?= $edad ?> años - Sexo: <?= $sexo ?></p>

    <form action="guardar_consulta_inicial.php" method="POST" class="mt-4">
      <input type="hidden" name="id_paciente" value="<?= $id_paciente ?>">

      <!-- MOTIVO --------------------------------------------------------->
      <div class="mb-4">
        <label class="form-label fw-semibold">Motivo de la consulta *</label>
        <textarea name="motivo" class="form-control" rows="2" required></textarea>
      </div>

      <div class="row">
        <!-- ENFERMEDADES PERSONALES -------------------------------------->
        <div class="col-md-6 mb-4">
          <label class="form-label fw-semibold">Enfermedades que padece o padeció</label>
          <?php
            $enfermedades = [
              'Sobrepeso u Obesidad',
              'Diabetes Mellitus',
              'Colesterol o Triglicéridos altos',
              'Hipertensión',
              'Enfermedades cardíacas',
              'Cáncer',
              'Problemas tiroideos',
              'Enfermedad gastrointestinal',
              'Enfermedad renal',
              'Otro'
            ];
            foreach ($enfermedades as $e):
          ?>
            <div class="form-check">
              <input class="form-check-input" type="checkbox"
                    name="enfermedades[]" value="<?= $e ?>" id="enf-<?= md5($e) ?>">
              <label class="form-check-label" for="enf-<?= md5($e) ?>"><?= $e ?></label>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- ANTECEDENTES FAMILIARES ------------------------------------->
        <div class="col-md-6 mb-4">
          <label class="form-label fw-semibold">Antecedentes familiares</label>
          <?php
            $antecedentes = $enfermedades;  // mismo listado
            foreach ($antecedentes as $a):
          ?>
            <div class="form-check">
              <input class="form-check-input" type="checkbox"
                    name="antecedentes[]" value="<?= $a ?>" id="ant-<?= md5($a) ?>">
              <label class="form-check-label" for="ant-<?= md5($a) ?>"><?= $a ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- MEDICACIÓN ----------------------------------------------------->
      <div class="mb-4">
        <label class="form-label fw-semibold">Medicamentos que consume</label>
        <textarea name="medicacion" class="form-control" rows="2"></textarea>
      </div>

      <!-- CIRUGÍAS / ALCOHOL / FUMADOR ---------------------------------->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold d-block">¿Se realizó alguna cirugía?</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="cirugias" value="1" required>
            <label class="form-check-label">Sí</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="cirugias" value="0" checked>
            <label class="form-check-label">No</label>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold d-block">¿Consume alcohol?</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="al-s" type="radio" name="alcohol" value="1" required>
            <label class="form-check-label" for="al-s">Sí</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" id="al-n" type="radio" name="alcohol" value="0" checked>
            <label class="form-check-label" for="al-n">No</label>
          </div>
          <input type="text" name="alcohol_frecuencia"
                class="form-control mt-2"
                placeholder="Frecuencia (ej.: ocasional, diario…)">
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold d-block">¿Fuma?</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="fumador" value="1" required>
            <label class="form-check-label">Sí</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="fumador" value="0" checked>
            <label class="form-check-label">No</label>
          </div>
        </div>
      </div>

      <!-- BOTONES -------------------------------------------------------->
      <button type="submit" class="btn btn-success">Registrar Consulta Inicial</button>
      <a href="index.php?seccion=consulta" class="btn btn-secondary">Cancelar</a>
    </form>

  </div>
</div>
