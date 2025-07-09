<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../vendor/autoload.php';

use Insoutt\EcValidator\EcValidator;

$tipo = $_POST['tipo_identificacion'] ?? '';
$numero = $_POST['ruc_cedula'] ?? '';

$tipo = trim($tipo);
$numero = trim($numero);

if (empty($tipo) || empty($numero)) {
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos para validar'
    ]);
    exit;
}

$validator = new EcValidator();

try {
    $valido = false;
    if (strcasecmp($tipo, 'Cedula') === 0) {
        $valido = $validator->validateCedula($numero);
    } elseif (strcasecmp($tipo, 'RUC') === 0) {
        $valido = $validator->validateRuc($numero);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de identificación no soportado'
        ]);
        exit;
    }

    if ($valido) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $validator->getError() ?: 'Número de identificación inválido'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en validación: ' . $e->getMessage()
    ]);
}
?>