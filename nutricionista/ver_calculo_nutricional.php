<?php
session_start();
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header("Location: ../auth/login.php");
    exit;
}

$id_consulta = $_GET['id_consulta'] ?? null;
if (!$id_consulta || !is_numeric($id_consulta)) {
    echo "<div class='alert alert-danger text-center mt-4'>ID de consulta no válido.</div>";
    require_once 'includes/footer.php';
    exit;
}

$stmt = $conn->prepare("
    SELECT p.nombre, p.apellido, p.dni, c.fecha,
           cn.imc, cn.ICC, cn.GEB, cn.GET, cn.RHE, 
           cn.calorias_por_kg, cn.calorias_totales, 
           cn.peso_ideal, cn.peso_ajustado, cn.peso_IA,
           hp.porcentaje_grasa, hp.porcentaje_muscular, 
           hp.cintura, hp.cadera, 
           hp.pliegue_tricipital, hp.pliegue_subescapular, hp.pliegue_suprailiaco
    FROM calculo_nutricional cn
    JOIN consulta c ON cn.id_consulta = c.id_consulta
    JOIN historial_paciente hp ON c.id_historial = hp.id_historial
    JOIN paciente pa ON c.id_paciente = pa.id_paciente
    JOIN usuario u ON pa.id_usuario = u.id_usuario
    JOIN persona p ON u.id_persona = p.id_persona
    WHERE cn.id_consulta = ?
");
$stmt->bind_param("i", $id_consulta);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

if (!$data) {
    echo "<div class='alert alert-warning text-center mt-4'>No se encontraron datos nutricionales para esta consulta.</div>";
    exit;
}
?>

<div class="main-content">
  <div class="contenido-central">
    <div class="card p-4 shadow-sm">
      <h2 class="titulo-seccion">Resultados Nutricionales</h2>
      <p><strong>Paciente:</strong> <?= htmlspecialchars($data['nombre']) . ' ' . htmlspecialchars($data['apellido']) ?> (DNI: <?= $data['dni'] ?>)</p>
      <p><strong>Fecha de Consulta:</strong> <?= date('d/m/Y', strtotime($data['fecha'])) ?></p>
      <hr>

      <h5 class="mt-3">📌 Evaluación Antropométrica</h5>
      <ul>
        <li><strong>IMC:</strong> <?= $data['imc'] ?> kg/m²</li>
        <li><strong>ICC:</strong> <?= $data['ICC'] ?></li>
        <li><strong>Peso Ideal:</strong> <?= $data['peso_ideal'] ?> kg</li>
        <li><strong>Peso Ajustado:</strong> <?= $data['peso_ajustado'] ?> kg</li>
      </ul>

      <h5 class="mt-3">⚡ Requerimiento Energético</h5>
      <ul>
        <li><strong>GEB:</strong> <?= $data['GEB'] ?> kcal</li>
        <li><strong>GET:</strong> <?= $data['GET'] ?> kcal</li>
      </ul>

      <h5 class="mt-3">🍽️ Macronutrientes</h5>
      <ul>
        <li><strong>Calorías por kg:</strong> <?= $data['calorias_por_kg'] ?> kcal/kg</li>
        <li><strong>Calorías totales:</strong> <?= $data['calorias_totales'] ?> kcal</li>
      </ul>

      <h5 class="mt-3">💧 Hidratación</h5>
      <ul>
        <li><strong>RHE:</strong> <?= $data['RHE'] ?> ml/día</li>
      </ul>

      <h5 class="mt-3">📊 Otros Datos</h5>
      <ul>
        <li><strong>% Grasa Corporal:</strong> <?= $data['porcentaje_grasa'] ?>%</li>
        <li><strong>% Masa Muscular:</strong> <?= $data['porcentaje_muscular'] ?>%</li>
        <li><strong>Cintura:</strong> <?= $data['cintura'] ?> cm</li>
        <li><strong>Cadera:</strong> <?= $data['cadera'] ?> cm</li>
        <li><strong>Pliegues:</strong> Tricipital <?= $data['pliegue_tricipital'] ?> mm, Subescapular <?= $data['pliegue_subescapular'] ?> mm, Suprailiaco <?= $data['pliegue_suprailiaco'] ?> mm</li>
      </ul>

      <a href="index.php?seccion=historial" class="btn btn-secondary mt-4">← Volver</a>
    </div>
  </div>
</div>
