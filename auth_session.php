<?php
session_start();

// Función segura para destruir sesión y redirigir
function cerrar_sesion_y_redirigir($motivo = '') {
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(), '', 0, '/');
    header("Location: login/index.php" . ($motivo ? "?motivo=" . urlencode($motivo) : ""));
    exit();
}

// Validar que el usuario esté logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    cerrar_sesion_y_redirigir('sesion_no_valida');
}

// Validar que exista un token CSRF
if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
    cerrar_sesion_y_redirigir('token_invalido');
}

// Expirar sesión por inactividad (ej. 30 minutos)
$timeout = 1800; // 1800 segundos = 30 minutos
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    cerrar_sesion_y_redirigir('sesion_expirada');
} else {
    $_SESSION['last_activity'] = time();
}
?>