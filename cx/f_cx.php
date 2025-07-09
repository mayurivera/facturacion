<?php
    require_once 'bd_cx.php';
    function autenticarUsuario($usuario, $password) {
        try {
            $db = conectarDB();

            $sql = "SELECT id_usuario, nombre, contraseña, estado, id_rol FROM usuarios WHERE nombre = :nombre LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nombre', $usuario, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Hash dummy para timing attack prevention
            $dummyHash = '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG'; 

            if (!$user) {
                // Simulamos password_verify para tiempo constante
                password_verify($password, $dummyHash);
                return [
                    'success' => false,
                    'error' => 'Usuario no encontrado o inactivo'
                ];
            }

            // Verificar contraseña
            if (!password_verify($password, $user['contraseña'])) {
                return [
                    'success' => false,
                    'error' => 'Contraseña incorrecta'
                ];
            }

            // Verificar estado activo
            if ($user['estado'] !== 'activo') {
                return [
                    'success' => false,
                    'error' => 'Usuario inactivo'
                ];
            }

            // Autenticación exitosa
            return [
                'success' => true,
                'data' => [
                    'id_usuario' => $user['id_usuario'],
                    'nombre' => $user['nombre'],
                    'id_rol' => $user['id_rol'],
                    'estado' => $user['estado']
                ]
            ];
        } catch (PDOException $e) {
            // Manejo de error DB (puede ser logueado)
            return [
                'success' => false,
                'error' => 'Error en la base de datos'
            ];
        }
    }

    function ejecutarConsultaSegura($sql, $params = [], $soloUno = false) {
        try {
            $db = conectarDB();
            $stmt = $db->prepare($sql);

            foreach ($params as $clave => $valor) {
                if (is_int($clave)) {
                    $stmt->bindValue($clave + 1, $valor);
                } else {
                    $stmt->bindValue($clave, $valor);
                }
            }

            $stmt->execute();

            if (preg_match('/^\s*(SELECT|SHOW|DESCRIBE|PRAGMA)/i', $sql)) {
                return $soloUno ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return [
                    'filas_afectadas' => $stmt->rowCount(),
                    'last_insert_id' => $db->lastInsertId()
                ];
            }

        } catch (PDOException $e) {
            throw $e;
        }
    }

?>
