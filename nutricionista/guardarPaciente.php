<?php
require_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar entradas
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // Validación básica
    if (!$nombre || !$apellido || !$dni || !$fecha_nacimiento || !$sexo || !$correo) {
        header('Location: index.php?seccion=pacientes&error=campos_obligatorios');
        exit;
    }

    try {
        // Comprobar si el DNI o correo ya existen
        $verificar = $conn->prepare("SELECT id_persona FROM persona WHERE dni = ? OR correo = ?");
        $verificar->bind_param("ss", $dni, $correo);
        $verificar->execute();
        $verificar->store_result();

        if ($verificar->num_rows > 0) {
            header('Location: index.php?seccion=pacientes&error=dni_o_correo_existente');
            exit;
        }

        // Iniciar transacción
        $conn->begin_transaction();

        // 1. Insertar en persona
        $stmt1 = $conn->prepare("INSERT INTO persona (nombre, apellido, dni, fecha_nacimiento, correo, telefono, tipoSexo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssssss", $nombre, $apellido, $dni, $fecha_nacimiento, $correo, $telefono, $sexo);
        $stmt1->execute();
        $id_persona = $conn->insert_id;

        // 2. Insertar en usuario con contraseña 123 hasheada
        $contrasena_hash = password_hash('123', PASSWORD_DEFAULT);
        $rol = 'paciente';
        $stmt2 = $conn->prepare("INSERT INTO usuario (id_persona, contrasena, rol) VALUES (?, ?, ?)");
        $stmt2->bind_param("iss", $id_persona, $contrasena_hash, $rol);
        $stmt2->execute();
        $id_usuario = $conn->insert_id;

        // 3. Insertar en paciente
        $stmt3 = $conn->prepare("INSERT INTO paciente (id_usuario, alergico, antecedentes_medicos) VALUES (?, NULL, NULL)");
        $stmt3->bind_param("i", $id_usuario);
        $stmt3->execute();

        // Confirmar y redirigir
        $conn->commit();
        header('Location: index.php?seccion=pacientes&exito=agregado');
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error al guardar paciente: " . $e->getMessage());
        header('Location: index.php?seccion=pacientes&error=registro_fallido');
        exit;
    }

} else {
    header('Location: index.php?seccion=pacientes');
    exit;
}
