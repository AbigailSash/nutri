<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Cargar imagen de perfil (si existe), sino usar por defecto
$foto = $_SESSION['foto_perfil'] ?? 'img/perfiles/default.png';
$rutaFoto = "/sistema_nutri/img/perfiles/" . basename($foto);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Nutricionista</title>

  <!-- ✅ Bootstrap 5.3 + íconos + fuentes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- ✅ Tus estilos personalizados -->
  <link rel="stylesheet" href="../css/panel_nutri.css">
</head>
<body>

<header class="topbar">
  <!-- Último ingreso -->
  <div class="last-login text-end">
    <div class="small text-muted">
      Último ingreso:
      <span class="badge bg-secondary">
        <?php echo date('d/m/Y H:i:s', strtotime($_SESSION['ultimo_login'])); ?>
      </span>
    </div>
  </div>

  <!-- Dropdown usuario con imagen -->
  <div class="dropdown">
    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="<?php echo htmlspecialchars($rutaFoto); ?>" class="profile-img" alt="Foto perfil">
      <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
      <li><a class="dropdown-item" href="/sistema_nutri/nutricionista/configuracion_nutri.php">Configuración</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item text-danger" href="/sistema_nutri/auth/logout.php">Cerrar sesión</a></li>
    </ul>
  </div>
</header>

<!-- ✅ Bootstrap JS Bundle (popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
