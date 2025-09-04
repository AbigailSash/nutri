<?php
// Parámetros de conexión
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$base_datos = 'sistema_nutricion';
$puerto = 3306;

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_datos, $puerto);

// Verificar conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Establecer codificación UTF-8
if (!$conn->set_charset("utf8mb4")) {
    die("❌ Error al establecer charset: " . $conn->error);
}

// Establecer zona horaria (Argentina UTC-3)
if (!$conn->query("SET time_zone = '-03:00'")) {
    die("❌ Error al establecer zona horaria: " . $conn->error);
}
?>
