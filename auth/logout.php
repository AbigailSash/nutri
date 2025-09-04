<?php
session_start();
require_once '../bd/conexion.php';

// Establecer zona horaria local para coherencia (aunque no es necesaria si MySQL se ajustó)
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Registrar salida de sesión si existe ID de sesión activa
if (isset($_SESSION['id_sesion'])) {
    $idSesion = $_SESSION['id_sesion'];

    // Llamar al procedimiento almacenado para registrar salida
    $stmt = $conn->prepare("CALL registrar_salida(?)");
    $stmt->bind_param("i", $idSesion);
    $stmt->execute();
    $stmt->close();
}

// Destruir sesión PHP
session_unset();
session_destroy();

// Redirigir al login
header("Location: ../auth/login.php");
exit;
