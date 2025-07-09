<?php
include_once 'cx/f_cx.php';

header('Content-Type: application/json');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sqlIva = "SELECT tarifa_iva FROM parametros_sri WHERE estado_registro = 'activo' LIMIT 1";
$resultIva = ejecutarConsultaSegura($sqlIva);
$tarifaIva = 15.00; // Por defecto

if ($resultIva && count($resultIva) > 0) {
    $tarifaIva = floatval($resultIva[0]['tarifa_iva']);
}

$sql = "SELECT id_producto, codigo, nombre, precio_unitario FROM productos WHERE estado = 'activo'";
$params = [];

if ($search !== '') {
    $sql .= " AND (codigo LIKE :search1 OR nombre LIKE :search2)";
    $params[':search1'] = '%' . $search . '%';
    $params[':search2'] = '%' . $search . '%';
}

$sql .= " ORDER BY nombre ASC LIMIT 10";

$productos = ejecutarConsultaSegura($sql, $params);

if ($productos === false) {
    echo json_encode(['error' => 'Error en la base de datos al obtener productos.']);
    exit;
}

foreach ($productos as &$producto) {
    $producto['tarifa_iva'] = $tarifaIva;
}

echo json_encode(['productos' => $productos]);
?>