<?php
session_start();
require_once '../bd/conexion.php';

// Establecer zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Obtener datos del formulario
$dni = $_POST['dni'];
$contrasena = $_POST['contrasena'];

// Buscar al usuario por DNI
$sql = "SELECT u.id_usuario, u.contrasena, u.rol, u.foto_perfil, p.nombre, p.apellido
        FROM Usuario u
        JOIN Persona p ON u.id_persona = p.id_persona
        WHERE p.dni = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dni);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario_bd = $resultado->fetch_assoc();

    if (password_verify($contrasena, $usuario_bd['contrasena'])) {

        // Guardar datos en sesión
        $_SESSION['id_usuario'] = $usuario_bd['id_usuario'];
        $_SESSION['nombre_completo'] = $usuario_bd['nombre'] . " " . $usuario_bd['apellido'];
        $_SESSION['rol'] = $usuario_bd['rol'];

        // Guardar ruta de imagen si existe
        $_SESSION['foto_perfil'] = $usuario_bd['foto_perfil'] ?? null;

        // Fecha ingreso (Argentina)
        $fecha_ingreso = date('Y-m-d H:i:s');
        $_SESSION['ultimo_login'] = $fecha_ingreso;

        // Registrar sesión
        $insertSesion = "INSERT INTO sesion (id_usuario, fecha_ingreso) VALUES (?, ?)";
        $stmtSesion = $conn->prepare($insertSesion);
        $stmtSesion->bind_param("is", $usuario_bd['id_usuario'], $fecha_ingreso);
        $stmtSesion->execute();
        $_SESSION['id_sesion'] = $stmtSesion->insert_id;

        // Redirigir por rol
        if ($usuario_bd['rol'] === 'paciente') {
            header("Location: /sistema_nutri/paciente/index.php");
        } elseif ($usuario_bd['rol'] === 'nutricionista') {
            header("Location: /sistema_nutri/nutricionista/index.php");
        }
        exit;

    } else {
        // Contraseña incorrecta
        header("Location: ../auth/login.php?error=1");
        exit;
    }

} else {
    // Usuario no encontrado
    header("Location: ../auth/login.php?error=1");
    exit;
}
