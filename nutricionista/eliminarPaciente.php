<?php
require_once '../bd/conexion.php';

$id_persona = $_GET['id_persona'] ?? null;
$id_usuario = $_GET['id_usuario'] ?? null;

// Obtener el id_paciente asociado
$stmt = $conn->prepare("SELECT id_paciente FROM paciente WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$id_paciente = ($row = $result->fetch_assoc()) ? $row['id_paciente'] : null;

if (!$id_persona || !$id_usuario || !$id_paciente) {
  header('Location: index.php?seccion=pacientes&error=datos_invalidos');
  exit;
}

try {
  $conn->begin_transaction();

  // 1. Eliminar cálculos nutricionales (por id_consulta del paciente)
  $conn->query("DELETE cn FROM calculo_nutricional cn
                JOIN consulta c ON cn.id_consulta = c.id_consulta
                WHERE c.id_paciente = $id_paciente");

  // 2. Eliminar planes nutricionales
  $conn->query("DELETE pn FROM plan_nutricional pn
                JOIN consulta c ON pn.id_consulta = c.id_consulta
                WHERE c.id_paciente = $id_paciente");

  // 3. Eliminar consultas
  $conn->query("DELETE FROM consulta WHERE id_paciente = $id_paciente");

  // 4. Eliminar historial
  $conn->query("DELETE FROM historial_paciente WHERE id_paciente = $id_paciente");

  // 5. Eliminar de paciente
  $stmt1 = $conn->prepare("DELETE FROM paciente WHERE id_paciente = ?");
  $stmt1->bind_param("i", $id_paciente);
  $stmt1->execute();

  // 6. Eliminar de usuario
  $stmt2 = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
  $stmt2->bind_param("i", $id_usuario);
  $stmt2->execute();

  // 7. Eliminar de persona
  $stmt3 = $conn->prepare("DELETE FROM persona WHERE id_persona = ?");
  $stmt3->bind_param("i", $id_persona);
  $stmt3->execute();

  $conn->commit();
  header('Location: index.php?seccion=pacientes&exito=eliminado');
} catch (Exception $e) {
  $conn->rollback();
  error_log("Error al eliminar paciente: " . $e->getMessage());
  header('Location: index.php?seccion=pacientes&error=eliminacion_fallida');
}
