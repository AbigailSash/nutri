<?php
session_start();
require_once '../bd/conexion.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/* ------------------------------------------------------------------
   0.  Control de acceso y método
-------------------------------------------------------------------*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header('Location: ../auth/login.php');
    exit;
}

/* ------------------------------------------------------------------
   1.  Datos recibidos del formulario
-------------------------------------------------------------------*/
$id_paciente  = $_POST['id_paciente']       ?? null;
$diagnostico  = trim($_POST['observaciones'] ?? '');   // texto libre

$peso         = floatval($_POST['peso']               ?? 0);
$altura       = floatval($_POST['altura']             ?? 0);
$porc_grasa   = floatval($_POST['porcentaje_grasa']   ?? 0);
$porc_musculo = floatval($_POST['porcentaje_muscular']?? 0);
$cintura      = floatval($_POST['cintura']            ?? 0);
$cadera       = floatval($_POST['cadera']             ?? 0);
$pl_tricipital   = floatval($_POST['pliegue_tricipital']  ?? 0);
$pl_subescapular = floatval($_POST['pliegue_subescapular']?? 0);
$pl_suprailiaco  = floatval($_POST['pliegue_suprailiaco'] ?? 0);

if (!$id_paciente || $peso <= 0 || $altura <= 0) {
    header('Location: index.php?seccion=consulta&error=valoresInvalidos');
    exit;
}

/* ------------------------------------------------------------------
   2.  Obtener id_nutricionista a partir del usuario logueado
-------------------------------------------------------------------*/
$id_usuario = $_SESSION['id_usuario'];
$stmtNutri  = $conn->prepare(
    "SELECT id_nutricionista FROM nutricionista WHERE id_usuario = ?"
);
$stmtNutri->bind_param('i', $id_usuario);
$stmtNutri->execute();
$id_nutricionista = $stmtNutri->get_result()->fetch_column();

if (!$id_nutricionista) {
    header('Location: index.php?seccion=consulta&error=nutriNoEncontrado');
    exit;
}

/* ------------------------------------------------------------------
   3.  Sexo y edad del paciente (para cálculos)
-------------------------------------------------------------------*/
$stmtDatos = $conn->prepare("
    SELECT p.tipoSexo, p.fecha_nacimiento
    FROM   paciente pa
    JOIN   usuario  u ON u.id_usuario  = pa.id_usuario
    JOIN   persona  p ON p.id_persona  = u.id_persona
    WHERE  pa.id_paciente = ?
");
$stmtDatos->bind_param('i', $id_paciente);
$stmtDatos->execute();
$datos = $stmtDatos->get_result()->fetch_assoc();

if (!$datos) {
    header('Location: index.php?seccion=consulta&error=noDatosPaciente');
    exit;
}

$sexo = strtolower($datos['tipoSexo']);
$edad = (new DateTime())->diff(new DateTime($datos['fecha_nacimiento']))->y;

/* ------------------------------------------------------------------
   4.  Cálculos nutricionales
-------------------------------------------------------------------*/
$altura_cm     = $altura * 100;
$imc           = round($peso / ($altura ** 2), 2);
$icc           = $cadera > 0 ? round($cintura / $cadera, 2) : null;
$peso_ideal    = $sexo === 'masculino' ? $altura_cm - 100 : $altura_cm - 104;
$peso_ajustado = round($peso_ideal + 0.25 * ($peso - $peso_ideal), 2);

$geb = $sexo === 'masculino'
     ? (10 * $peso) + (6.25 * $altura_cm) - (5 * $edad) + 5
     : (10 * $peso) + (6.25 * $altura_cm) - (5 * $edad) - 161;
$geb = round($geb, 2);

$get         = round($geb * 1.4, 2);
$rhe         = round($peso * 35, 2);
$cal_por_kg  = round($get / $peso, 2);
$cal_totales = $get;

/* ------------------------------------------------------------------
   5.  Transacción: consulta + historial + cálculos
-------------------------------------------------------------------*/
$conn->begin_transaction();

try {
    /* 5.1 Consulta */
    $stmtConsulta = $conn->prepare("
        INSERT INTO consulta
              (id_paciente, id_nutricionista, fecha, diagnostico)
        VALUES (?, ?, NOW(), ?)
    ");
    $stmtConsulta->bind_param('iis', $id_paciente, $id_nutricionista, $diagnostico);
    $stmtConsulta->execute();
    $id_consulta = $conn->insert_id;

    /* 5.2 Historial antropométrico */
    $stmtHist = $conn->prepare("
        INSERT INTO historial_paciente (
            id_paciente, fecha_medicion, peso, altura, porcentaje_grasa,
            porcentaje_muscular, cintura, cadera, pliegue_tricipital,
            pliegue_subescapular, pliegue_suprailiaco
        ) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmtHist->bind_param(
        'iddddddddd',
        $id_paciente, $peso, $altura, $porc_grasa, $porc_musculo,
        $cintura, $cadera, $pl_tricipital, $pl_subescapular, $pl_suprailiaco
    );
    $stmtHist->execute();
    $id_historial = $conn->insert_id;

    /* Asociar historial con la consulta recién creada */
    $stmtUpd = $conn->prepare("
        UPDATE consulta SET id_historial = ? WHERE id_consulta = ?
    ");
    $stmtUpd->bind_param('ii', $id_historial, $id_consulta);
    $stmtUpd->execute();

    /* 5.3 Cálculos nutricionales */
    $stmtCalc = $conn->prepare("
        INSERT INTO calculo_nutricional (
            id_consulta, imc, ICC, GEB, GET, RHE,
            calorias_por_kg, calorias_totales,
            peso_ideal, peso_ajustado, peso_IA
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmtCalc->bind_param(
        'idddddddddd',
        $id_consulta, $imc, $icc, $geb, $get, $rhe,
        $cal_por_kg, $cal_totales,
        $peso_ideal, $peso_ajustado, $peso
    );
    $stmtCalc->execute();

    $conn->commit();
    header("Location: ver_calculo_nutricional.php?id_consulta={$id_consulta}");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    die('❌ Error al guardar la consulta: ' . $e->getMessage());
}
?>
