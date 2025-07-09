<?php
require_once 'datos_cx.php';

function conectarDB() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    try {
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Manejo de errores
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays asociativos
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa consultas preparadas reales
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        return $pdo;

    } catch (PDOException $e) {
        // Evita mostrar errores sensibles en producción
        error_log('Error de conexión: ' . $e->getMessage());
        die('Error al conectar con la base de datos');
    }
}
?>
