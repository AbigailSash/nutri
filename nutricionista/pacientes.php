<?php
require_once '../bd/conexion.php';

$buscar = $_GET['buscar'] ?? '';
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$limite = 10;
$offset = ($paginaActual - 1) * $limite;

// Total de pacientes
$countSql = "SELECT COUNT(*) AS total FROM Paciente pa
  JOIN Usuario u ON pa.id_usuario = u.id_usuario
  JOIN Persona p ON u.id_persona = p.id_persona
  WHERE CONCAT(p.nombre, ' ', p.apellido, p.dni) LIKE ?";
$stmtCount = $conn->prepare($countSql);
$param = "%$buscar%";
$stmtCount->bind_param("s", $param);
$stmtCount->execute();
$totalPacientes = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPaginas = ceil($totalPacientes / $limite);

// Consulta con IDs para acciones
$sql = "SELECT p.id_persona, u.id_usuario, p.nombre, p.apellido, p.dni, p.telefono, p.correo
        FROM Paciente pa
        JOIN Usuario u ON pa.id_usuario = u.id_usuario
        JOIN Persona p ON u.id_persona = p.id_persona
        WHERE CONCAT(p.nombre, ' ', p.apellido, p.dni) LIKE ?
        ORDER BY p.apellido
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $param, $limite, $offset);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<div class="main-content">
  <div class="contenido-central">
    <h3 class="titulo-seccion">Gestión de Pacientes</h3>

<?php if (isset($_GET['exito'])): ?>
  <?php if ($_GET['exito'] === 'agregado'): ?>
    <div class="alert alert-success">✅ Paciente agregado correctamente.</div>
  <?php elseif ($_GET['exito'] === 'eliminado'): ?>
    <div class="alert alert-success">🗑️ Paciente eliminado correctamente.</div>
  <?php elseif ($_GET['exito'] === 'editado'): ?>
    <div class="alert alert-success">✏️ Paciente actualizado correctamente.</div>
  <?php endif; ?>

<?php elseif (isset($_GET['error']) && $_GET['error'] === 'campos_obligatorios'): ?>
  <div class="alert alert-warning">⚠️ Completá todos los campos obligatorios.</div>

<?php elseif (isset($_GET['error'])): ?>
  <div class="alert alert-danger">❌ Ocurrió un error inesperado.</div>
<?php endif; ?>

    <!-- Buscador y botón Agregar -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <form method="get" class="d-flex w-100 me-2">
        <input type="hidden" name="seccion" value="pacientes">
        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar paciente..." value="<?= htmlspecialchars($buscar) ?>">
        <button class="btn btn-primary">Buscar</button>
      </form>
      <button id="btnAgregar" class="btn btn-agregar" type="button" onclick="mostrarFormulario()">➕ Nuevo Paciente</button>
    </div>

    <!-- Formulario (Collapse) -->
    <div class="collapse mb-4" id="formNuevoPaciente">
      <div class="card card-body bg-light border position-relative">
        <button type="button" class="btn-close cerrar-formulario" aria-label="Cerrar" onclick="cerrarFormulario()"></button>

        <form action="guardarPaciente.php" method="post">
          <div class="row">
            <div class="col-md-6 mb-2"><input name="nombre" class="form-control" placeholder="Nombre" required></div>
            <div class="col-md-6 mb-2"><input name="apellido" class="form-control" placeholder="Apellido" required></div>
            <div class="col-md-6 mb-2"><input name="dni" class="form-control" placeholder="DNI" required></div>
            <div class="col-md-6 mb-2"><input name="fecha_nacimiento" type="date" class="form-control" required></div>
            <div class="col-md-6 mb-2">
              <select name="sexo" class="form-control" required>
                <option value="" disabled selected hidden>Sexo</option>
                <option value="femenino">Femenino</option>
                <option value="masculino">Masculino</option>
              </select>
            </div>
            <div class="col-md-6 mb-2"><input name="telefono" class="form-control" placeholder="Teléfono"></div>
            <div class="col-md-6 mb-2"><input name="correo" type="email" class="form-control" placeholder="Correo electrónico" required></div>
            <div class="col-md-6 mb-2"><input name="direccion" class="form-control" placeholder="Dirección"></div>
          </div>
          <button class="btn btn-primary mt-3">Guardar paciente</button>
        </form>
      </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive tabla-pacientes">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre']) ?></td>
              <td><?= htmlspecialchars($row['apellido']) ?></td>
              <td><?= htmlspecialchars($row['dni']) ?></td>
              <td><?= htmlspecialchars($row['telefono']) ?></td>
              <td><?= htmlspecialchars($row['correo']) ?></td>
              <td>
                <a href="editarPaciente.php?id_persona=<?= $row['id_persona'] ?>&id_usuario=<?= $row['id_usuario'] ?>"
                   class="btn btn-accion btn-editar" title="Editar">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <a href="eliminarPaciente.php?id_persona=<?= $row['id_persona'] ?>&id_usuario=<?= $row['id_usuario'] ?>"
                   class="btn btn-accion btn-eliminar"
                   onclick="return confirm('¿Estás seguro que deseas eliminar este paciente?')"
                   title="Eliminar">
                  <i class="bi bi-trash-fill"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Paginación -->
      <nav aria-label="Paginación de pacientes" class="mt-3">
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
              <a class="page-link" href="?seccion=pacientes&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>">
                <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  function mostrarFormulario() {
    const form = document.getElementById('formNuevoPaciente');
    const btn = document.getElementById('btnAgregar');
    const bsCollapse = new bootstrap.Collapse(form, { toggle: false });
    bsCollapse.show();
    btn.style.display = 'none';
  }

  function cerrarFormulario() {
    const form = document.getElementById('formNuevoPaciente');
    const btn = document.getElementById('btnAgregar');
    const bsCollapse = bootstrap.Collapse.getInstance(form);
    if (bsCollapse) {
      bsCollapse.hide();
    }
    btn.style.display = 'inline-block';
  }
</script>
