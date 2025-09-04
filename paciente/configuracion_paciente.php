<?php
session_start();
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'paciente') {
    header('Location: ../auth/login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";

// Procesar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hubo_cambio = false;

    // === IMAGEN ===
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['foto']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $nuevoNombre = "img/perfiles/perfil_" . $id_usuario . "." . $ext;
        $destino = "../" . $nuevoNombre;

        if (move_uploaded_file($tmpName, $destino)) {
            $stmt = $conn->prepare("UPDATE usuario SET foto_perfil = ? WHERE id_usuario = ?");
            $stmt->bind_param("si", $nuevoNombre, $id_usuario);
            $stmt->execute();
            $_SESSION['foto_perfil'] = $nuevoNombre . '?v=' . time(); // evitar caché
            $mensaje .= "🖼️ Imagen de perfil actualizada.<br>";
            $hubo_cambio = true;
        } else {
            $mensaje .= "❌ Error al subir la imagen.<br>";
        }
    }

    // === CONTRASEÑA ===
    $actual    = $_POST['contrasena_actual'] ?? '';
    $nueva     = $_POST['nueva_contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($actual || $nueva || $confirmar) {
        if (!$actual || !$nueva || !$confirmar) {
            $mensaje .= "⚠️ Completa todos los campos de contraseña.<br>";
        } else {
            $stmt = $conn->prepare("SELECT contrasena FROM usuario WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $res = $stmt->get_result();
            $user = $res->fetch_assoc();

            if (password_verify($actual, $user['contrasena'])) {
                if ($nueva === $actual) {
                    $mensaje .= "❌ La nueva contraseña no puede ser igual a la actual.<br>";
                } elseif ($nueva === $confirmar) {
                    $hash = password_hash($nueva, PASSWORD_DEFAULT);
                    $update = $conn->prepare("UPDATE usuario SET contrasena = ? WHERE id_usuario = ?");
                    $update->bind_param("si", $hash, $id_usuario);
                    $update->execute();
                    $mensaje .= "✅ Contraseña actualizada.<br>";
                    $hubo_cambio = true;
                } else {
                    $mensaje .= "❌ Las contraseñas no coinciden.<br>";
                }
            } else {
                $mensaje .= "❌ Contraseña actual incorrecta.<br>";
            }
        }
    }

    if (!$hubo_cambio && $mensaje === "") {
        $mensaje = "⚠️ No se realizaron cambios.";
    }

    $_SESSION['mensaje'] = $mensaje;
    header("Location: configuracion_paciente.php");
    exit;
}

// Mostrar mensaje
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>

<div class="main-content">
  <div class="contenido-central">
    <div class="card p-4" style="max-width: 700px; margin: auto; border-radius: 12px;">
      <h3 class="mb-4">Configuración del perfil</h3>

      <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?= $mensaje ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <!-- Actualizar imagen -->
        <h6 class="mb-3">Actualizar imagen de perfil</h6>
        <div class="mb-3">
          <label for="foto" class="form-label">Seleccionar imagen</label>
          <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>

        <hr class="my-4">

        <!-- Cambio de contraseña -->
        <h6 class="mb-3">Cambiar contraseña</h6>
        <div class="mb-3">
          <label for="contrasena_actual" class="form-label">Contraseña actual</label>
          <input type="password" name="contrasena_actual" id="contrasena_actual" class="form-control">
        </div>
        <div class="mb-3">
          <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
          <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control">
        </div>
        <div class="mb-4">
          <label for="confirmar_contrasena" class="form-label">Confirmar nueva contraseña</label>
          <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control">
        </div>

        <button type="submit" class="btn btn-guardar">Guardar cambios</button>
        <a href="index.php" class="btn btn-cancelar ms-2">Cancelar</a>
      </form>
    </div>
  </div>
</div>
