<?php
$seccion_activa = $_GET['seccion'] ?? '';
?>

<div class="sidebar">
  <h4 class="mb-4">
    <img src="../img/logo-def.png" alt="Logo NutriSalud">
    <span class="ms-2">NutriSalud</span>
  </h4>

  <a href="index.php" class="nav-link <?= $seccion_activa === '' ? 'active' : '' ?>">
    <img src="../img/icons/home.svg" alt="Inicio"> Inicio
  </a>

  <hr>

  <h6 class="text-muted">Pacientes</h6>
  <a href="index.php?seccion=pacientes" class="nav-link <?= $seccion_activa === 'pacientes' ? 'active' : '' ?>">
     Gestión de Pacientes
  </a>

  <h6 class="text-muted mt-3">Consultas</h6>
  <a href="index.php?seccion=consulta" class="nav-link <?= $seccion_activa === 'consulta' ? 'active' : '' ?>">
    Nueva Consulta
  </a>
  <a href="index.php?seccion=historial" class="nav-link <?= $seccion_activa === 'historial' ? 'active' : '' ?>">
    Historial
  </a>

  <h6 class="text-muted mt-3">Planes</h6>
  <a href="index.php?seccion=plan" class="nav-link <?= $seccion_activa === 'plan' ? 'active' : '' ?>">
    Plan Alimentario
  </a>

  <h6 class="text-muted mt-3">Seguimiento</h6>
  <a href="index.php?seccion=seguimiento" class="nav-link <?= $seccion_activa === 'seguimiento' ? 'active' : '' ?>">
    Ver Gráficos (Proximamente)
  </a>
  
</div>

