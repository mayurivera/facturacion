<?php
session_start();
include 'cx/f_cx.php'; // Asegúrate de que esta función retorna una instancia PDO válida

$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$db = conectarDB();

if ($action == 'get') {
    $sql = "SELECT 
                u.id_usuario, 
                u.nombre, 
                u.correo, 
                u.estado, 
                r.nombre_rol
            FROM usuarios u
            JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.id_usuario = ? AND u.estado_registro = 'activo'";

    $stmt = $db->prepare($sql);
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($usuario ?: []);
    exit;
}

if ($action == 'save') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';

    if ($nombre === '' || $correo === '') {
        echo json_encode(['success' => false, 'message' => 'Nombre y correo son requeridos.']);
        exit;
    }

    try {
        if ($contraseña !== '') {
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET 
                        nombre = ?, 
                        correo = ?, 
                        contraseña = ?, 
                        fecha_modificacion = CURRENT_TIMESTAMP, 
                        usuario_modificacion = ?
                    WHERE id_usuario = ? AND estado_registro = 'activo'";
            $stmt = $db->prepare($sql);
            $stmt->execute([$nombre, $correo, $hash, $id_usuario, $id_usuario]);
        } else {
            $sql = "UPDATE usuarios SET 
                        nombre = ?, 
                        correo = ?, 
                        fecha_modificacion = CURRENT_TIMESTAMP, 
                        usuario_modificacion = ?
                    WHERE id_usuario = ? AND estado_registro = 'activo'";
            $stmt = $db->prepare($sql);
            $stmt->execute([$nombre, $correo, $id_usuario, $id_usuario]);
        }

        // Manejo de la foto de perfil
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $destino = __DIR__ . "/assets/img/users/{$id_usuario}.jpg";
                $tmp = $_FILES['foto']['tmp_name'];
                // Convertir a JPG siempre
                if ($ext === 'jpg' || $ext === 'jpeg') {
                    move_uploaded_file($tmp, $destino);
                } else {
                    // Convertir PNG/GIF a JPG
                    if ($ext === 'png') {
                        $img = imagecreatefrompng($tmp);
                    } elseif ($ext === 'gif') {
                        $img = imagecreatefromgif($tmp);
                    } else {
                        $img = imagecreatefromstring(file_get_contents($tmp));
                    }
                    if ($img) {
                        imagejpeg($img, $destino, 90);
                        imagedestroy($img);
                    }
                }
            }
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción no válida']);
?>