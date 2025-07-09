<?php
session_start();
header('Content-Type: application/json');
include_once '../cx/f_cx.php';

// Sanitiza texto
function limpiarTexto($txt) {
    return trim(htmlspecialchars($txt, ENT_QUOTES, 'UTF-8'));
}

// Recoger datos
$id_cliente  = $_POST['cliente'] ?? null;
$fecha       = $_POST['fecha'] ?? null;
$forma_pago  = $_POST['forma_pago'] ?? null;
$estab       = $_POST['establecimiento'] ?? '001';
$punto       = $_POST['punto_emision'] ?? '001';

$id_productos   = $_POST['producto_id']    ?? [];
$cantidades     = $_POST['cantidad']       ?? [];
$precios_unit   = $_POST['precio_unitario']?? [];

if (!$id_cliente || !$fecha || !$forma_pago) {
    echo json_encode(['exito'=>false,'mensaje'=>'Faltan datos obligatorios.']);
    exit;
}
if (!count($id_productos)) {
    echo json_encode(['exito'=>false,'mensaje'=>'Debe agregar al menos un producto.']);
    exit;
}

try {
    $pdo = conectarDB();
    $pdo->beginTransaction();

    // Obtener siguiente secuencia
    $stmtSeq = $pdo->prepare("SELECT MAX(secuencia) AS max_seq
        FROM facturas WHERE establecimiento=:est AND punto_emision=:pto");
    $stmtSeq->execute([':est'=>$estab,':pto'=>$punto]);
    $seq = intval($stmtSeq->fetchColumn()) + 1;

    // Calcular subtotal
    $subtotal = 0;
    foreach ($cantidades as $i => $cant) {
        $subtotal += floatval($cant)*floatval($precios_unit[$i]);
    }
    $iva   = round($subtotal*0.12,2);
    $total = round($subtotal+$iva,2);

    // Generar clave_acceso dummy
    $clave = str_pad(rand(), 49, '0', STR_PAD_LEFT);

    $id_usuario = $_SESSION['id_usuario'] ?? null;
    if (!$id_usuario) throw new Exception("Usuario no autenticado");

    // Insertar factura
    $sqlF = "INSERT INTO facturas 
      (establecimiento,punto_emision,secuencia,clave_acceso,fecha_emision,
       id_cliente,id_usuario,subtotal,iva,total,estado,tipo_emision,ambiente,
       estado_registro,usuario_creacion,origen_dato)
     VALUES 
      (:est,:pto,:seq,:clave,:fecha,
       :cli,:usr,:subt,:iva,:tot,'emitida','normal','pruebas',
       'activo',:usr,'web')";
    $stmt = $pdo->prepare($sqlF);
    $stmt->execute([
      ':est'=>$estab,':pto'=>$punto,':seq'=>$seq,':clave'=>$clave,':fecha'=>$fecha,
      ':cli'=>$id_cliente,':usr'=>$id_usuario,
      ':subt'=>$subtotal,':iva'=>$iva,':tot'=>$total
    ]);
    $id_factura = $pdo->lastInsertId();

    // Insertar detalle_factura
    $sqlD = "INSERT INTO detalle_factura 
      (id_factura,id_producto,cantidad,precio_unitario,total_linea,
       usuario_creacion,origen_dato)
     VALUES 
      (:idf,:prod,:cant,:pu,:tl,:usr,'web')";
    $stmtD = $pdo->prepare($sqlD);

    foreach ($id_productos as $i => $pid) {
        $cant = floatval($cantidades[$i]);
        $pu   = floatval($precios_unit[$i]);
        $tl   = round($cant*$pu,2);
        if ($cant<=0||$pu<0) throw new Exception("Detalle inválido");
        $stmtD->execute([
          ':idf'=>$id_factura,':prod'=>$pid,
          ':cant'=>$cant,':pu'=>$pu,':tl'=>$tl,
          ':usr'=>$id_usuario
        ]);
    }

    $pdo->commit();
    echo json_encode(['exito'=>true,'mensaje'=>'Factura emitida.','id_factura'=>$id_factura]);

} catch(Exception $e) {
    if ($pdo && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['exito'=>false,'mensaje'=>'Error: '.$e->getMessage()]);
}
?>