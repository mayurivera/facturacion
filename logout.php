<?php include_once 'auth_session.php'; ?>

<?php
session_start();

// Borrar todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la cookie de sesión, también se debe hacer:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login
header("Location: login/index.php");
exit();
?>