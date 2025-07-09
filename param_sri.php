<?php
include_once "cx/f_cx.php";
header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $stmt = conectarDB()->query("SELECT * FROM parametros_sri");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $stmt = conectarDB()->prepare("SELECT * FROM parametros_sri WHERE id_parametro = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC) ?: []);
        break;

    case 'save':
        $db = conectarDB();
        $isNew = empty($_POST['id_parametro']);
        $params = [
            ':ambiente' => $_POST['ambiente'],
            ':url_autorizacion' => $_POST['url_autorizacion'],
            ':url_recepcion' => $_POST['url_recepcion'],
            ':dominio_produccion' => $_POST['dominio_produccion'],
            ':dominio_pruebas' => $_POST['dominio_pruebas'],
            ':ruc_emisor' => $_POST['ruc_emisor'],
            ':razon_social' => $_POST['razon_social'],
            ':nombre_comercial' => $_POST['nombre_comercial'],
            ':origen_dato' => $_POST['origen_dato'],
        ];

        if ($isNew) {
            $sql = "INSERT INTO parametros_sri (
                ambiente, url_autorizacion, url_recepcion,
                dominio_produccion, dominio_pruebas,
                ruc_emisor, razon_social, nombre_comercial,
                estado_registro, usuario_creacion, origen_dato,
                fecha_registro
            ) VALUES (
                :ambiente, :url_autorizacion, :url_recepcion,
                :dominio_produccion, :dominio_pruebas,
                :ruc_emisor, :razon_social, :nombre_comercial,
                'activo', 1, :origen_dato,
                NOW()
            )";
        } else {
            $sql = "UPDATE parametros_sri SET
                ambiente = :ambiente, url_autorizacion = :url_autorizacion, url_recepcion = :url_recepcion,
                dominio_produccion = :dominio_produccion, dominio_pruebas = :dominio_pruebas,
                ruc_emisor = :ruc_emisor, razon_social = :razon_social, nombre_comercial = :nombre_comercial,
                origen_dato = :origen_dato, usuario_modificacion = 1, fecha_modificacion = NOW()
                WHERE id_parametro = :id_parametro";
            $params[':id_parametro'] = $_POST['id_parametro'];
        }

        $stmt = $db->prepare($sql);
        echo json_encode(['success' => $stmt->execute($params)]);
        break;

    case 'estado':
        $id = $_POST['id'] ?? null;
        $estado = $_POST['estado'] ?? '';
        if (!in_array($estado, ['activo', 'inactivo']) || !is_numeric($id)) {
            echo json_encode(['success' => false]);
            break;
        }
        $stmt = conectarDB()->prepare("UPDATE parametros_sri SET estado_registro = ?, fecha_modificacion = NOW() WHERE id_parametro = ?");
        echo json_encode(['success' => $stmt->execute([$estado, $id])]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
}
?>