<?php
require_once '../cx/f_cx.php'; // Asegúrate de ajustar la ruta a tu archivo de conexión

try {
    $sql = "SELECT codigo_forma_pago, descripcion FROM formas_pago_catalogo WHERE estado_registro = 'activo'";
    $formasPago = ejecutarConsultaSegura($sql);

    header('Content-Type: application/json');
    echo json_encode($formasPago);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener las formas de pago']);
}
?>