<?php
header('Content-Type: application/json');
include_once '../cx/f_cx.php';

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

// Búsqueda segura con LIKE
$sql = "SELECT id_cliente, razon_social, ruc_cedula 
        FROM clientes 
        WHERE estado='activo' 
          AND (razon_social LIKE :term OR ruc_cedula LIKE :term)
        LIMIT 10";
$term = "%$q%";
$result = ejecutarConsultaSegura($sql, [':term' => $term]);

echo json_encode($result);
?>