<?php
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$buscar = $_GET['buscar'] ?? '';
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $porPagina;

$buscarLike = "%$buscar%";

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

$sql = "SELECT p.id_paciente, per.nombre, per.apellido, per.dni
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
    <h2 class="titulo-seccion">Historial de Pacientes</h2>

    <form method="GET" action="">
      <input type="hidden" name="seccion" value="historial">
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
            <th>DNI</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $pacientes->fetch_assoc()): ?>
          <tr>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['apellido'] ?></td>
            <td><?= $row['dni'] ?></td>
            <td>
              <a href="ver_historial.php?id_paciente=<?= $row['id_paciente'] ?>" class="btn btn-secondary btn-sm">Historial</a>
              <a href="ver_plan.php?id_paciente=<?= $row['id_paciente'] ?>" class="btn btn-success btn-sm">Ver Plan</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div style="margin-top: 1rem; display: flex; justify-content: center;">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="index.php?seccion=historial&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>"
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
