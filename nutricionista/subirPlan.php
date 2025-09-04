<?php
require_once '../bd/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plan_archivo']) && isset($_POST['id_paciente'])) {
    $id_paciente = intval($_POST['id_paciente']);
    $archivo = $_FILES['plan_archivo'];

    // Validar MIME y extensión
    $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $mime = mime_content_type($archivo['tmp_name']);

    if ($ext !== 'pdf' || $mime !== 'application/pdf') {
        die("❌ Solo se permiten archivos PDF válidos.");
    }

    // Obtener datos del paciente
    $stmt = $conn->prepare("
        SELECT per.nombre, per.apellido
        FROM paciente p
        JOIN usuario u ON p.id_usuario = u.id_usuario
        JOIN persona per ON u.id_persona = per.id_persona
        WHERE p.id_paciente = ?
    ");
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if (!$resultado) {
        die("❌ Paciente no encontrado.");
    }

    // Generar nombre de archivo sin espacios
    $nombre = preg_replace('/\s+/', '', $resultado['nombre']);
    $apellido = preg_replace('/\s+/', '', $resultado['apellido']);
    $fecha = date('Ymd_His');
    $nombreArchivo = "Plan_{$nombre}{$apellido}_{$fecha}.pdf";

    // Ruta donde se guardará
    $carpetaDestino = '../nutricionista/planes_nutricionales/';
    $rutaFinal = $carpetaDestino . $nombreArchivo;

    // Asegurar existencia de carpeta
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0775, true);
    }

    // Mover archivo
    if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
        die("❌ Error al mover el archivo al servidor.");
    }

    // Registrar en base de datos
    $rutaBD = "nutricionista/planes_nutricionales/" . $nombreArchivo;
    $stmtInsert = $conn->prepare("
        INSERT INTO plan_nutricional (id_paciente, archivo_pdf, fecha_entrega, tipo_plan, objetivo, duracion, descripcion)
        VALUES (?, ?, NOW(), '', '', '', '')
    ");
    $stmtInsert->bind_param("is", $id_paciente, $rutaBD);
    if ($stmtInsert->execute()) {
        header("Location: index.php?seccion=plan&exito=1");
        exit;
    } else {
        echo "❌ Error al guardar el plan en la base de datos.";
    }
} else {
    echo "⚠️ Datos incompletos o mal enviados.";
}
