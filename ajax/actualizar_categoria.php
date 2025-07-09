<?php
header('Content-Type: application/json');
include_once "../cx/f_cx.php";

// Validar los datos requeridos
if (
    empty($_POST['id_categoria']) ||
    empty($_POST['nombre_categoria']) ||
    !isset($_POST['estado'])
) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

$id = intval($_POST['id_categoria']);
$nombre = trim($_POST['nombre_categoria']);
$descripcion = trim($_POST['descripcion'] ?? '');
$estado = trim($_POST['estado']);

// Validar estado permitido (opcional pero recomendado)
$estados_validos = ['activo', 'inactivo', 'eliminado'];
if (!in_array($estado, $estados_validos)) {
    echo json_encode(['success' => false, 'message' => 'Estado inválido']);
    exit;
}

// Ejecutar UPDATE
$sql = "UPDATE categorias 
        SET nombre_categoria = :nombre, 
            descripcion = :descripcion, 
            estado = :estado 
        WHERE id_categoria = :id";

$params = [
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':estado' => $estado,
    ':id' => $id
];

$resultado = ejecutarConsultaSegura($sql, $params);

// Evaluar resultado
if ($resultado && isset($resultado['filas_afectadas']) && $resultado['filas_afectadas'] > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se actualizó ningún registro'
    ]);
}
?>