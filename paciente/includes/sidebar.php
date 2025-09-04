<?php
$seccion_activa = $_GET['seccion'] ?? '';
?>

<div class="sidebar">
  <h4 class="mb-4">
    <img src="../img/logo-def.png" alt="Logo NutriSalud">
    <span class="ms-2">NutriSalud</span>
  </h4>

  <a href="index.php" class="nav-link <?= $seccion_activa === '' ? 'active' : '' ?>">
    <img src="../img/icons/home.svg" alt="Inicio">
    <span>Inicio</span>
  </a>

  <hr>

  <h6 class="text-muted mt-3">Consultas</h6>

  <a href="index.php?seccion=historial" class="nav-link <?= $seccion_activa === 'historial' ? 'active' : '' ?>">
    <span>Historial</span>
  </a>

  <a href="index.php?seccion=ver_plan" class="nav-link <?= $seccion_activa === 'plan' ? 'active' : '' ?>">
    <span>Plan Alimentario</span>
  </a>
  

  <a href="index.php?seccion=graficos" class="nav-link <?= $seccion_activa === 'graficos' ? 'active' : '' ?>">
    <span>Ver Gráficos</span>
  </a>
</div>
