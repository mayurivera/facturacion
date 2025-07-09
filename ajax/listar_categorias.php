<?php
header('Content-Type: application/json');
include_once "../cx/f_cx.php";

$sql = "SELECT id_categoria, nombre_categoria FROM categorias WHERE estado = 'activo'";
$resultado = ejecutarConsultaSegura($sql);

if ($resultado && count($resultado) > 0) {
    echo json_encode($resultado);
} else {
    echo json_encode([]);
}
?>
