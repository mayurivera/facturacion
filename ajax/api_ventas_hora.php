<?php
include_once 'db.php';
$hoy = date('Y-m-d');
$horas = [];
$valores = [];
for ($h = 0; $h < 24; $h++) {
    $horas[] = sprintf('%02d:00', $h);
    $valores[] = 0;
}
$sql = "
    SELECT HOUR(fecha_emision) as hora, SUM(total) as total
    FROM facturas
    WHERE DATE(fecha_emision) = '$hoy' AND estado_registro = 'activo'
    GROUP BY hora
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $valores[(int)$row['hora']] = (float)$row['total'];
}
header('Content-Type: application/json');
echo json_encode(['horas' => $horas, 'valores' => $valores]);
?>