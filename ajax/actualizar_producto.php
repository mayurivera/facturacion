<?php
header('Content-Type: application/json');
include_once "../cx/f_cx.php";

// Validar los datos requeridos para actualizar producto
if (
    empty($_POST['id_producto']) ||
    empty($_POST['codigo']) ||
    empty($_POST['nombre']) ||
    empty($_POST['id_categoria']) ||
    !isset($_POST['estado'])  // puede ser 'activo' o 'inactivo'
) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

$id_producto = intval($_POST['id_producto']);
$codigo = trim($_POST['codigo']);
$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion'] ?? '');
$id_categoria = intval($_POST['id_categoria']);
$precio_unitario = floatval($_POST['precio_unitario'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);
$estado = trim($_POST['estado']);

// Validar estado permitido (opcional)
$estados_validos = ['activo', 'inactivo'];
if (!in_array($estado, $estados_validos)) {
    echo json_encode(['success' => false, 'message' => 'Estado inválido']);
    exit;
}

// Aquí la consulta SQL para actualizar producto:
$sql = "UPDATE productos SET
            codigo = :codigo,
            nombre = :nombre,
            descripcion = :descripcion,
            id_categoria = :id_categoria,
            precio_unitario = :precio_unitario,
            stock = :stock,
            estado = :estado
        WHERE id_producto = :id_producto";

$params = [
    ':codigo' => $codigo,
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':id_categoria' => $id_categoria,
    ':precio_unitario' => $precio_unitario,
    ':stock' => $stock,
    ':estado' => $estado,
    ':id_producto' => $id_producto
];

$resultado = ejecutarConsultaSegura($sql, $params);

// Para obtener el nombre de la categoría actualizado y devolverlo:
$sql_categoria = "SELECT nombre_categoria FROM categorias WHERE id_categoria = :id_categoria";
$res_categoria = ejecutarConsultaSegura($sql_categoria, [':id_categoria' => $id_categoria]);

$nombre_categoria = $res_categoria && count($res_categoria) > 0 ? $res_categoria[0]['nombre_categoria'] : '';

if ($resultado && isset($resultado['filas_afectadas']) && $resultado['filas_afectadas'] > 0) {
    echo json_encode(['success' => true, 'nombre_categoria' => $nombre_categoria]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se actualizó ningún registro']);
}
?>