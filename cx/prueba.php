<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');   // Cambia a IP o localhost según tu configuración
define('DB_NAME', 'facturacion');
define('DB_USER', 'facturar');    // Asegúrate que este usuario existe en MySQL
define('DB_PASS', '12345');
define('DB_CHARSET', 'utf8mb4');

// Crear conexión
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($mysqli->connect_errno) {
    echo "Error de conexión: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

echo "Conexión exitosa a la base de datos '" . DB_NAME . "' con usuario '" . DB_USER . "'";

// Opcional: cambiar charset a utf8mb4
if (!$mysqli->set_charset(DB_CHARSET)) {
    echo "Error cargando el conjunto de caracteres: " . $mysqli->error;
    exit();
}

$mysqli->close();
?>
