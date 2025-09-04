<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Paciente</title>
  <link rel="stylesheet" href="../css/panel_nutri.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php
require_once '../bd/conexion.php';

$id_persona = $_GET['id_persona'] ?? null;
$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_persona || !$id_usuario) {
    header('Location: index.php?seccion=pacientes&error=datos_invalidos');
    exit;
}

$sql = "SELECT p.nombre, p.apellido, p.dni, p.fecha_nacimiento, p.correo, p.telefono, p.tipoSexo
        FROM persona p
        JOIN usuario u ON p.id_persona = u.id_persona
        WHERE p.id_persona = ? AND u.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_persona, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    header('Location: index.php?seccion=pacientes&error=no_encontrado');
    exit;
}

$datos = $resultado->fetch_assoc();
?>

<div class="main-content">
  <div class="contenido-central">
    <h3 class="titulo-seccion"><i class="bi bi-pencil-fill"></i> Editar Paciente</h3>

    <form action="actualizarPaciente.php" method="post">
      <input type="hidden" name="id_persona" value="<?= $id_persona ?>">
      <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

      <div class="row">
        <div class="col-md-6 mb-2">
          <input name="nombre" class="form-control" placeholder="Nombre" required value="<?= htmlspecialchars($datos['nombre']) ?>">
        </div>
        <div class="col-md-6 mb-2">
          <input name="apellido" class="form-control" placeholder="Apellido" required value="<?= htmlspecialchars($datos['apellido']) ?>">
        </div>
        <div class="col-md-6 mb-2">
          <input name="dni" class="form-control" placeholder="DNI" required value="<?= htmlspecialchars($datos['dni']) ?>">
        </div>
        <div class="col-md-6 mb-2">
          <input name="fecha_nacimiento" type="date" class="form-control" required value="<?= $datos['fecha_nacimiento'] ?>">
        </div>
        <div class="col-md-6 mb-2">
          <select name="sexo" class="form-control" required>
            <option value="" disabled hidden>Sexo</option>
            <option value="femenino" <?= $datos['tipoSexo'] === 'femenino' ? 'selected' : '' ?>>Femenino</option>
            <option value="masculino" <?= $datos['tipoSexo'] === 'masculino' ? 'selected' : '' ?>>Masculino</option>
          </select>
        </div>
        <div class="col-md-6 mb-2">
          <input name="telefono" class="form-control" placeholder="Teléfono" value="<?= htmlspecialchars($datos['telefono']) ?>">
        </div>
        <div class="col-md-6 mb-2">
          <input name="correo" type="email" class="form-control" placeholder="Correo electrónico" required value="<?= htmlspecialchars($datos['correo']) ?>">
        </div>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="index.php?seccion=pacientes" class="btn btn-secondary">← Volver al listado</a>
        <button class="btn btn-primary">Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
