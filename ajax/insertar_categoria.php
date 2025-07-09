<?php
header('Content-Type: application/json');
include_once "../cx/f_cx.php";

// Validar datos
if (empty($_POST['nombre_categoria']) || !isset($_POST['estado'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
    exit;
}

$nombre = trim($_POST['nombre_categoria']);
$descripcion = trim($_POST['descripcion'] ?? '');
$estado = $_POST['estado']; // No convertir a int, ya que es ENUM 'activo' o 'inactivo'

$sql = "INSERT INTO categorias (nombre_categoria, descripcion, estado) 
        VALUES (:nombre, :descripcion, :estado)";
$params = [
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':estado' => $estado
];

$resultado = ejecutarConsultaSegura($sql, $params);

if ($resultado && $resultado['filas_afectadas'] > 0) {
    echo json_encode([
        'success' => true,
        'id_categoria' => $resultado['last_insert_id'],
        'nombre_categoria' => $nombre,
        'descripcion' => $descripcion,
        'estado' => $estado
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo insertar la categoría'
    ]);
}
?>