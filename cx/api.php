<?php
// Asegúrate de que este archivo no se pueda acceder directamente sin las funciones de conexión.
define('AJAX_REQUEST', true); 

// Incluye tu archivo de funciones de conexión y consulta segura
require_once 'f_cx.php'; 

header('Content-Type: application/json');

$action = $_GET['action'] ?? ''; // Obtener la acción solicitada

switch ($action) {
    case 'getEstablecimientos':
        $sql = "SELECT id_establecimiento, codigo, nombre FROM establecimientos WHERE estado = 'activo' ORDER BY codigo ASC";
        $establecimientos = ejecutarConsultaSegura($sql);
        
        if ($establecimientos !== false) {
            echo json_encode($establecimientos);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'No se pudieron obtener los establecimientos.']);
        }
        break;

    case 'getPuntosEmision':
        $id_establecimiento = $_GET['id_establecimiento'] ?? null;

        if ($id_establecimiento === null || !is_numeric($id_establecimiento)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'ID de establecimiento no proporcionado o inválido.']);
            exit;
        }

        $sql = "SELECT id_punto, codigo, descripcion FROM puntos_emision WHERE id_establecimiento = :id_establecimiento AND estado = 'activo' ORDER BY codigo ASC";
        $params = [':id_establecimiento' => $id_establecimiento];
        $puntosEmision = ejecutarConsultaSegura($sql, $params);

        if ($puntosEmision !== false) {
            echo json_encode($puntosEmision);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'No se pudieron obtener los puntos de emisión.']);
        }
        break;

    case 'getClienteByIdentificacion':
        $ruc_cedula = $_GET['ruc_cedula'] ?? null; // Usar ruc_cedula
        $tipo_identificacion = $_GET['tipoIdentificacion'] ?? null; 

        if ($ruc_cedula === null || empty($ruc_cedula)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Identificación (RUC/Cédula) no proporcionada.']);
            exit;
        }

        // La tabla 'clientes' no tiene 'F' o 'E' en el ENUM, solo 'RUC', 'Cedula', 'Pasaporte'.
        // Si el tipo es 'Consumidor final' o 'Identificación del exterior', no intentamos buscar en la tabla 'clientes'
        // ya que estos suelen tener un manejo especial (ej. RUC 9999999999999 o no búsqueda).
        // Si necesitas buscar por 'Pasaporte' para 'Identificación del exterior', la lógica deberá ser más específica.
        if (!in_array($tipo_identificacion, ['RUC', 'Cedula', 'Pasaporte'])) {
            echo json_encode(['cliente' => null]); // No se busca en la tabla clientes para estos tipos
            exit;
        }

        // Modificar la consulta para usar 'ruc_cedula' y 'correo'
        $sql = "SELECT id_cliente, razon_social, ruc_cedula, tipo_identificacion, direccion, telefono, correo 
                FROM clientes 
                WHERE ruc_cedula = :ruc_cedula AND tipo_identificacion = :tipo_identificacion AND estado_registro = 'activo'";
        
        $params = [
            ':ruc_cedula' => $ruc_cedula,
            ':tipo_identificacion' => $tipo_identificacion
        ];

        $cliente = ejecutarConsultaSegura($sql, $params, true); // Usar true para obtener solo un registro
        
        if ($cliente !== false) {
            if (empty($cliente)) {
                echo json_encode(['cliente' => null]); // Cliente no encontrado
            } else {
                echo json_encode(['cliente' => $cliente]); // Devuelve el cliente encontrado
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Error al buscar el cliente en la base de datos.']);
        }
        break;

    
        $sql = "SELECT tarifa_iva FROM parametros_sri WHERE estado_registro = 'activo' LIMIT 1";
        try {
            $parametro = ejecutarConsultaSegura($sql, [], true); // true para soloUno
            if ($parametro === false) {
                echo json_encode(['error' => 'Error en la base de datos al obtener tarifa IVA.']);
            } elseif ($parametro && isset($parametro['tarifa_iva'])) {
                echo json_encode(['tarifa_iva_default' => (float)$parametro['tarifa_iva']]);
            } else {
                echo json_encode(['error' => 'Tarifa de IVA predeterminada no encontrada o no configurada.']);
            }
        } catch (Exception $e) {
            error_log("Error al obtener tarifa IVA predeterminada: " . $e->getMessage());
            echo json_encode(['error' => 'Error interno del servidor al obtener tarifa IVA.']);
        }
        break;

    default:
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Acción no válida.']);
        break;
}
?>