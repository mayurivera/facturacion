<?php
header('Content-Type: application/json');
include_once "../cx/f_cx.php"; // ruta a conexión, ajustar según estructura

// Recibir datos POST
$razon_social = $_POST['razon_social'] ?? '';
$ruc_cedula = $_POST['ruc_cedula'] ?? '';
$tipo_identificacion = $_POST['tipo_identificacion'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';
$estado = $_POST['estado'] ?? '';

if (!$razon_social || !$ruc_cedula || !$tipo_identificacion || !$estado) {
    echo json_encode(['success' => false, 'message' => 'Campos obligatorios incompletos']);
    exit;
}

try {
    $db = conectarDB(); // función de conexión desde f_cx.php

    $sql = "INSERT INTO facturacion.clientes 
        (razon_social, ruc_cedula, tipo_identificacion, direccion, telefono, correo, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->execute([$razon_social, $ruc_cedula, $tipo_identificacion, $direccion, $telefono, $correo, $estado]);

    $id = $db->lastInsertId();

    $cliente = [
        'id_cliente' => $id,
        'razon_social' => $razon_social,
        'ruc_cedula' => $ruc_cedula,
        'tipo_identificacion' => $tipo_identificacion,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'correo' => $correo,
        'estado' => $estado
    ];

    echo json_encode(['success' => true, 'cliente' => $cliente]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
exit;
