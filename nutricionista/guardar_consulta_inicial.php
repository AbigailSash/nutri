<?php
session_start();
require_once '../bd/conexion.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'nutricionista') {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

/* ------------------------------------------------------------------
   1.  Recoger y sanear datos
-------------------------------------------------------------------*/
$id_paciente  = intval($_POST['id_paciente'] ?? 0);
$motivo       = trim($_POST['motivo'] ?? '');

$enfermedades = isset($_POST['enfermedades']) && is_array($_POST['enfermedades'])
              ? implode(', ', $_POST['enfermedades'])
              : '';

$antecedentes = isset($_POST['antecedentes']) && is_array($_POST['antecedentes'])
              ? implode(', ', $_POST['antecedentes'])
              : '';

$medicacion   = trim($_POST['medicacion'] ?? '');

$cirugias     = isset($_POST['cirugias']) ? (int)$_POST['cirugias'] : 0;
$alcohol      = isset($_POST['alcohol'])  ? (int)$_POST['alcohol']  : 0;

$alcohol_freq = ($alcohol === 1)
              ? trim($_POST['alcohol_frecuencia'] ?? '')
              : null;                                // NULL si no bebe

$fumador      = isset($_POST['fumador'])  ? (int)$_POST['fumador']  : 0;

if (!$id_paciente || $motivo === '') {
    $_SESSION['mensaje'] = "<div class='alert alert-danger text-center'>Datos incompletos.</div>";
    header("Location: registrar_consulta_inicial.php?id_paciente=$id_paciente");
    exit;
}

/* ------------------------------------------------------------------
   2.  Verificar que no exista ya una consulta inicial
-------------------------------------------------------------------*/
$check = $conn->prepare("
    SELECT 1
    FROM   consulta_inicial ci
    JOIN   consulta        c ON c.id_consulta = ci.id_consulta
    WHERE  c.id_paciente = ?
    LIMIT  1
");
$check->bind_param('i', $id_paciente);
$check->execute();
$check->store_result();

if ($check->num_rows) {
    $_SESSION['mensaje'] = "<div class='alert alert-warning text-center'>
                            Este paciente ya tiene una consulta inicial.
                            </div>";
    header("Location: registrar_consulta_inicial.php?id_paciente=$id_paciente");
    exit;
}

/* ------------------------------------------------------------------
   3.  Obtener id_nutricionista desde la sesión
-------------------------------------------------------------------*/
$id_usuario = $_SESSION['id_usuario'];
$stmtNutri  = $conn->prepare("
    SELECT id_nutricionista
    FROM   nutricionista
    WHERE  id_usuario = ?
");
$stmtNutri->bind_param('i', $id_usuario);
$stmtNutri->execute();
$id_nutricionista = $stmtNutri->get_result()->fetch_column();

if (!$id_nutricionista) {
    $_SESSION['mensaje'] = "<div class='alert alert-danger text-center'>Nutricionista no válido.</div>";
    header("Location: registrar_consulta_inicial.php?id_paciente=$id_paciente");
    exit;
}

/* ------------------------------------------------------------------
   4.  Transacción: INSERT en consulta y consulta_inicial
-------------------------------------------------------------------*/
$conn->begin_transaction();

try {
    /* 4.1 tabla consulta */
    $stmt = $conn->prepare("
        INSERT INTO consulta (id_paciente, id_nutricionista, fecha)
        VALUES ( ?, ?, NOW() )
    ");
    $stmt->bind_param('ii', $id_paciente, $id_nutricionista);
    $stmt->execute();
    $id_consulta = $conn->insert_id;

    /* 4.2 tabla consulta_inicial */
    $stmt2 = $conn->prepare("
        INSERT INTO consulta_inicial
          (id_consulta, motivo, enfermedades, antecedentes,
           medicacion, cirugias, alcohol, alcohol_frecuencia, fumador)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");
    /* tipos: i s s s s i i s i  -> "issssiisi" */
    $stmt2->bind_param(
        'isssssisi',
        $id_consulta,
        $motivo,
        $enfermedades,
        $antecedentes,
        $medicacion,
        $cirugias,
        $alcohol,
        $alcohol_freq,
        $fumador
    );
    $stmt2->execute();

    $conn->commit();

    $_SESSION['mensaje'] = "<div class='alert alert-success text-center'>
                            Consulta inicial registrada correctamente.
                            </div>";
    header("Location: ver_historial.php?id_paciente=$id_paciente");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['mensaje'] = "<div class='alert alert-danger text-center'>
                            Error al registrar: {$e->getMessage()}
                            </div>";
    header("Location: registrar_consulta_inicial.php?id_paciente=$id_paciente");
    exit;
}
