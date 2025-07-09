<?php
include 'cx/f_cx.php';

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    $sql = "SELECT u.*, r.nombre_rol 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id_rol 
            WHERE u.estado_registro != 'eliminado'";
    echo json_encode(ejecutarConsultaSegura($sql));
}

if ($action === 'get' && isset($_GET['id'])) {
    $sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
    $data = ejecutarConsultaSegura($sql, [':id' => $_GET['id']], true);
    echo json_encode($data);
}

if ($action === 'save') {
    $id = $_POST['id_usuario'] ?? '';
    $params = [
        ':nombre' => $_POST['nombre'],
        ':correo' => $_POST['correo'],
        ':id_rol' => $_POST['id_rol'],
        ':estado' => $_POST['estado'],
        ':origen' => 'modulo_gestion',
        ':usuario' => 1 // reemplazar con $_SESSION['id_usuario'] si aplica
    ];

    $passSql = '';
    if (!empty($_POST['contraseña'])) {
        $params[':pass'] = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
        $passSql = ", contraseña = :pass";
    }

    if ($id) {
        $params[':id'] = $id;
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, correo = :correo, id_rol = :id_rol, estado = :estado, 
                    fecha_modificacion = NOW(), usuario_modificacion = :usuario, origen_dato = :origen
                    $passSql
                WHERE id_usuario = :id";
    } else {
        $params[':pass'] = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios 
                (nombre, correo, contraseña, id_rol, estado, estado_registro, fecha_creacion, fecha_registro, usuario_creacion, origen_dato) 
                VALUES (:nombre, :correo, :pass, :id_rol, :estado, 'activo', NOW(), NOW(), :usuario, :origen)";
    }

    $res = ejecutarConsultaSegura($sql, $params);
    echo $res !== false ? "ok" : "error";
}

if ($action === 'disable' && isset($_GET['id'])) {
    $sql = "UPDATE usuarios 
            SET estado = 'inactivo', estado_registro = 'inactivo', fecha_modificacion = NOW(), usuario_modificacion = :usuario 
            WHERE id_usuario = :id";
    $res = ejecutarConsultaSegura($sql, [':id' => $_GET['id'], ':usuario' => 1]);
    echo $res !== false ? "ok" : "error";
}
?>