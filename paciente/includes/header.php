<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../bd/conexion.php';
date_default_timezone_set('America/Argentina/Buenos_Aires');

$id_usuario = $_SESSION['id_usuario'] ?? null;
$nombre_completo = 'Paciente';
$foto = $_SESSION['foto_perfil'] ?? 'img/perfiles/default.png';
$rutaFoto = "/sistema_nutri/" . $foto;
$ultimo_login = '';

// Obtener datos desde la base de datos
if ($id_usuario) {
    // Nombre completo
    $stmt = $conn->prepare("
        SELECT CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo 
        FROM usuario u 
        JOIN persona p ON u.id_persona = p.id_persona 
        WHERE u.id_usuario = ?
    ");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $nombre_completo = $res->fetch_assoc()['nombre_completo'] ?? 'Paciente';
    $_SESSION['nombre_completo'] = $nombre_completo;

    // Último ingreso
    $stmt2 = $conn->prepare("SELECT fecha_ingreso FROM sesion WHERE id_usuario = ? ORDER BY fecha_ingreso DESC LIMIT 1");
    $stmt2->bind_param("i", $id_usuario);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $ultimo_login = $res2->fetch_assoc()['fecha_ingreso'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Paciente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + íconos + fuentes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="../css/panel_paciente.css">
  <link rel="icon" href="../img/logo-def.png">
</head>
<body>

<!-- ✅ HEADER UNIFICADO -->
<header class="topbar">
  <div class="last-login text-end">
    <div class="small text-muted">
      Último ingreso:
      <span class="badge bg-secondary">
        <?= $ultimo_login ? date('d/m/Y H:i:s', strtotime($ultimo_login)) : 'N/D'; ?>
      </span>
    </div>
  </div>

  <!-- Dropdown usuario -->
  <div class="dropdown">
    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="dropdownUserPaciente" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="<?= htmlspecialchars($rutaFoto) ?>" class="profile-img" alt="Foto perfil">
      <?= htmlspecialchars($nombre_completo) ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUserPaciente">
      <li><a class="dropdown-item" href="configuracion_paciente.php">Configuración</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item text-danger" href="../auth/logout.php">Cerrar sesión</a></li>
    </ul>
  </div>
</header>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
