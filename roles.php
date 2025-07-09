<?php
include 'cx/f_cx.php';

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    $sql = "SELECT * FROM roles WHERE estado != 'eliminado'";
    echo json_encode(ejecutarConsultaSegura($sql));
}

if ($action === 'get' && isset($_GET['id'])) {
    $sql = "SELECT * FROM roles WHERE id_rol = :id";
    $data = ejecutarConsultaSegura($sql, [':id' => $_GET['id']], true);
    echo json_encode($data);
}

if ($action === 'save') {
    $id = $_POST['id_rol'] ?? '';
    $params = [
        ':nombre' => $_POST['nombre_rol'],
        ':descripcion' => $_POST['descripcion'] ?? null,
        ':estado' => $_POST['estado']
    ];

    if ($id) {
        $sql = "UPDATE roles SET nombre_rol = :nombre, descripcion = :descripcion, estado = :estado WHERE id_rol = :id";
        $params[':id'] = $id;
    } else {
        $sql = "INSERT INTO roles (nombre_rol, descripcion, estado) VALUES (:nombre, :descripcion, :estado)";
    }

    $res = ejecutarConsultaSegura($sql, $params);
    echo $res !== false ? "ok" : "error";
}

if ($action === 'disable' && isset($_GET['id'])) {
    $sql = "UPDATE roles SET estado = 'inactivo' WHERE id_rol = :id";
    $res = ejecutarConsultaSegura($sql, [':id' => $_GET['id']]);
    echo $res !== false ? "ok" : "error";
}
?>