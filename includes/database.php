<?php
// Mostrar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servidor = "localhost";
$usuario = "root";
$contraseña = "123456";
$base_datos = "agenda_contactos";

$dsn = "mysql:host=$servidor;dbname=$base_datos;charset=utf8";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
];

try {
    $conn = new PDO($dsn, $usuario, $contraseña, $opciones);
    // Comentario para debug - quitar en producción
    // echo "<!-- Conexión exitosa -->";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>