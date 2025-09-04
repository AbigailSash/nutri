<?php
require_once '../bd/conexion.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
  <div class="contenido-central">
    <?php
    if (isset($_GET['id_paciente'])) {
        $id_paciente = $_GET['id_paciente'];

        $stmt = $conn->prepare("SELECT p.nombre, p.apellido, p.dni
                                FROM paciente pa
                                JOIN usuario u ON pa.id_usuario = u.id_usuario
                                JOIN persona p ON u.id_persona = p.id_persona
                                WHERE pa.id_paciente = ?");
        $stmt->bind_param("i", $id_paciente);
        $stmt->execute();
        $result = $stmt->get_result();
        $paciente = $result->fetch_assoc();

        if (!$paciente) {
            echo "<div class='alert alert-danger text-center'>❌ Paciente no encontrado.</div>";
            exit;
        }

        if (isset($_GET['guardado']) && $_GET['guardado'] === 'ok') {
            echo "<div class='alert alert-success text-center'>✅ Consulta registrada correctamente.</div>";
        }
        if (isset($_GET['error']) && $_GET['error'] === 'datos') {
            echo "<div class='alert alert-danger text-center'>❌ Datos incompletos. Verificá los campos obligatorios.</div>";
        }
        if (isset($_GET['error']) && $_GET['error'] === 'db') {
            echo "<div class='alert alert-danger text-center'>❌ Error al registrar los datos en la base de datos.</div>";
        }
    ?>

    <h2 class="titulo-seccion">Nueva Consulta para <?= htmlspecialchars($paciente['nombre']) . " " . htmlspecialchars($paciente['apellido']) ?> (DNI: <?= $paciente['dni'] ?>)</h2>

    <p>Para registrar una nueva consulta de seguimiento nutricional, utilizá el siguiente botón:</p>

    <div class="mt-4">
      <a href="nueva_consulta_nutricional.php?id_paciente=<?= $id_paciente ?>" class="btn btn-success">Registrar Seguimiento</a>
      <a href="index.php?seccion=consulta" class="btn btn-secondary">Volver</a>
    </div>

    <?php
    } else {
        $buscar = $_GET['buscar'] ?? '';
        $porPagina = 10;
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $offset = ($pagina - 1) * $porPagina;

        $sqlTotal = "SELECT COUNT(*) as total FROM Persona p 
                     JOIN Usuario u ON p.id_persona = u.id_persona 
                     JOIN Paciente pa ON u.id_usuario = pa.id_usuario
                     WHERE CONCAT(p.nombre, ' ', p.apellido, p.dni) LIKE ?";
        $stmtTotal = $conn->prepare($sqlTotal);
        $paramBuscar = "%$buscar%";
        $stmtTotal->bind_param("s", $paramBuscar);
        $stmtTotal->execute();
        $totalRegistros = $stmtTotal->get_result()->fetch_assoc()['total'];
        $totalPaginas = ceil($totalRegistros / $porPagina);

        $sql = "SELECT p.nombre, p.apellido, p.dni, pa.id_paciente FROM Persona p 
                JOIN Usuario u ON p.id_persona = u.id_persona 
                JOIN Paciente pa ON u.id_usuario = pa.id_usuario
                WHERE CONCAT(p.nombre, ' ', p.apellido, p.dni) LIKE ?
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $paramBuscar, $porPagina, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if (isset($_GET['guardado']) && $_GET['guardado'] === 'ok') {
            echo "<div class='alert alert-success text-center'>✅ Consulta registrada correctamente.</div>";
        }
        if (isset($_GET['error']) && $_GET['error'] === 'datos') {
            echo "<div class='alert alert-danger text-center'>❌ Datos incompletos. Verificá los campos obligatorios.</div>";
        }
        if (isset($_GET['error']) && $_GET['error'] === 'db') {
            echo "<div class='alert alert-danger text-center'>❌ Error al registrar los datos en la base de datos.</div>";
        }
    ?>

    <h2 class="titulo-seccion">Nueva Consulta</h2>

    <form class="form-busqueda mb-3" method="GET" action="index.php">
      <input type="hidden" name="seccion" value="consulta">
      <input type="text" class="form-control" name="buscar" value="<?= htmlspecialchars($buscar) ?>" placeholder="Buscar paciente...">
      <button type="submit" class="btn btn-primary">Buscar</button>
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
        <?php while ($fila = $resultado->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($fila['nombre']) ?></td>
            <td><?= htmlspecialchars($fila['apellido']) ?></td>
            <td><?= htmlspecialchars($fila['dni']) ?></td>
            <td>
              <?php
              $tieneConsultaInicial = false;
              $verifica = $conn->prepare("SELECT ci.id_inicial FROM consulta_inicial ci
                                          JOIN consulta c ON ci.id_consulta = c.id_consulta
                                          WHERE c.id_paciente = ? LIMIT 1");
              $verifica->bind_param("i", $fila['id_paciente']);
              $verifica->execute();
              $verifica->store_result();
              $tieneConsultaInicial = $verifica->num_rows > 0;
              ?>

              <?php if (!$tieneConsultaInicial): ?>
                <a href="registrar_consulta_inicial.php?id_paciente=<?= $fila['id_paciente'] ?>" class="btn btn-primary btn-sm">Consulta Inicial</a>
              <?php else: ?>
                <span class="text-muted small">Consulta inicial registrada</span>
              <?php endif; ?>

              <a href="nueva_consulta_nutricional.php?id_paciente=<?= $fila['id_paciente'] ?>" class="btn btn-success btn-sm">Seguimiento</a>
              <?php
              $stmtCons = $conn->prepare("SELECT id_consulta FROM consulta WHERE id_paciente = ? ORDER BY fecha DESC LIMIT 1");
              $stmtCons->bind_param("i", $fila['id_paciente']);
              $stmtCons->execute();
              $r = $stmtCons->get_result()->fetch_assoc();
              $id_ultima_consulta = $r['id_consulta'] ?? null;
              ?>
              <a href="<?= $id_ultima_consulta ? 'ver_calculo_nutricional.php?id_consulta=' . $id_ultima_consulta : '#' ?>" 
                 class="btn btn-info btn-sm <?= $id_ultima_consulta ? '' : 'disabled' ?>">Ver Resultados</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <nav>
      <ul class="pagination justify-content-center mt-4">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
          <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
            <a class="page-link" href="index.php?seccion=consulta&pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>

    <?php } ?>
  </div>
</div>
