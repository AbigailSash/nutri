<?php
require_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_persona = $_POST['id_persona'] ?? null;
    $id_usuario = $_POST['id_usuario'] ?? null;

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    if (!$id_persona || !$id_usuario || !$nombre || !$apellido || !$dni || !$fecha_nacimiento || !$sexo || !$correo) {
        header('Location: index.php?seccion=pacientes&error=campos_obligatorios');
        exit;
    }

    try {
        $conn->begin_transaction();

        // Actualizar persona
        $stmt = $conn->prepare("UPDATE persona SET nombre = ?, apellido = ?, dni = ?, fecha_nacimiento = ?, correo = ?, telefono = ?, tipoSexo = ? WHERE id_persona = ?");
        $stmt->bind_param("sssssssi", $nombre, $apellido, $dni, $fecha_nacimiento, $correo, $telefono, $sexo, $id_persona);
        $stmt->execute();

        $conn->commit();
        header('Location: index.php?seccion=pacientes&exito=editado');
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error al editar paciente: " . $e->getMessage());
        header('Location: index.php?seccion=pacientes&error=1');
        exit;
    }

} else {
    header('Location: index.php?seccion=pacientes');
    exit;
}
