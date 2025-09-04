<?php
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$buscar = $_GET['buscar'] ?? '';
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $porPagina;

$buscarLike = "%$buscar%";

// Total de pacientes
$sqlTotal = "SELECT COUNT(*) as total
             FROM paciente p
             JOIN usuario u ON p.id_usuario = u.id_usuario
             JOIN persona per ON u.id_persona = per.id_persona
             WHERE per.nombre LIKE ? OR per.apellido LIKE ?";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->bind_param("ss", $buscarLike, $buscarLike);
$stmtTotal->execute();
$total = $stmtTotal->get_result()->fetch_assoc()['total'];
$totalPaginas = ceil($total / $porPagina);

// Pacientes paginados
$sql = "SELECT p.id_paciente, per.nombre, per.apellido
        FROM paciente p
        JOIN usuario u ON p.id_usuario = u.id_usuario
        JOIN persona per ON u.id_persona = per.id_persona
        WHERE per.nombre LIKE ? OR per.apellido LIKE ?
        LIMIT $porPagina OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $buscarLike, $buscarLike);
$stmt->execute();
$pacientes = $stmt->get_result();
?>

<div class="main-content">
  <div class="contenido-central">
    <h2 class="titulo-seccion">Plan Alimentario</h2>

    <?php if (isset($_GET['exito']) && $_GET['exito'] == 1): ?>
      <div class="alert alert-success">✅ Plan alimentario cargado correctamente.</div>
    <?php endif; ?>

    <form method="GET" action="" style="margin-bottom: 1.5rem;">
      <input type="hidden" name="seccion" value="plan">
      <div style="display: flex; gap: 10px;">
        <input type="text" name="buscar" placeholder="Buscar paciente..." value="<?= htmlspecialchars($buscar) ?>" style="flex:1; padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc;">
        <button type="submit" class="btn btn-violeta" style="padding: 8px 20px;">Buscar</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while($row = $pacientes->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['apellido']) ?></td>
            <td>
              <button class="btn btn-violeta btn-sm" onclick="mostrarFormulario(<?= $row['id_paciente'] ?>)">Cargar Plan</button>

              <form class="form-plan" id="form-<?= $row['id_paciente'] ?>" action="subirPlan.php" method="POST" enctype="multipart/form-data" style="display:none; margin-top:8px;">
                <input type="hidden" name="id_paciente" value="<?= $row['id_paciente'] ?>">
                <div style="display: flex; flex-direction: column; gap: 10px;">
                  <input type="file" name="plan_archivo" accept=".pdf" required>
                  <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success btn-sm">✅ Subir Plan</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="ocultarFormulario(<?= $row['id_paciente'] ?>)">Cancelar</button>
                  </div>
                </div>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="paginacion" style="margin-top: 1rem; display: flex; justify-content: center;">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="index.php?seccion=plan&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>"
           style="margin: 0 4px; padding: 6px 12px; border-radius: 6px; text-decoration: none;
                  background-color: <?= ($i == $pagina) ? '#b39ddb' : '#fff' ?>;
                  color: <?= ($i == $pagina) ? '#fff' : '#222' ?>;
                  border: 1px solid #ccc; font-weight: 500;">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
</div>

<script>
function mostrarFormulario(id) {
  document.getElementById('form-' + id).style.display = 'block';
}
function ocultarFormulario(id) {
  document.getElementById('form-' + id).style.display = 'none';
}
</script>
