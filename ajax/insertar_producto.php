<?php
header('Content-Type: application/json');
include_once "../cx/f_cx.php";

// Validar campos requeridos
if (
    empty($_POST['codigo']) ||
    empty($_POST['nombre']) ||
    !isset($_POST['id_categoria']) ||
    !isset($_POST['precio_unitario']) ||
    !isset($_POST['stock']) ||
    !isset($_POST['estado'])
) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

$codigo = trim($_POST['codigo']);
$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion'] ?? '');
$id_categoria = (int)$_POST['id_categoria'];
$precio_unitario = floatval($_POST['precio_unitario']);
$stock = intval($_POST['stock']);
$estado = $_POST['estado']; // ENUM: 'activo' o 'inactivo'

$sql = "INSERT INTO productos (codigo, nombre, descripcion, id_categoria, precio_unitario, stock, estado)
        VALUES (:codigo, :nombre, :descripcion, :id_categoria, :precio_unitario, :stock, :estado)";
$params = [
    ':codigo' => $codigo,
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':id_categoria' => $id_categoria,
    ':precio_unitario' => $precio_unitario,
    ':stock' => $stock,
    ':estado' => $estado
];

$resultado = ejecutarConsultaSegura($sql, $params);

if ($resultado && $resultado['filas_afectadas'] > 0) {
    // Obtener el nombre de la categoría para devolver al front
    $sqlCat = "SELECT nombre_categoria FROM categorias WHERE id_categoria = :id";
    $resCat = ejecutarConsultaSegura($sqlCat, [':id' => $id_categoria], true);
    $nombre_categoria = $resCat['nombre_categoria'] ?? 'Desconocida';

    echo json_encode([
        'success' => true,
        'id_producto' => $resultado['last_insert_id'],
        'nombre_categoria' => $nombre_categoria
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo insertar el producto'
    ]);
}
?>