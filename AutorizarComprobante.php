<?php
// AutorizarComprobante.php
$claveAcceso = "1234567890123456789012345678901234567890123456789";

$wsdl = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl";

try {
    $client = new SoapClient($wsdl);
    $params = array("claveAccesoComprobante" => $claveAcceso);
    $response = $client->__soapCall("autorizacionComprobante", array($params));

    foreach ($response->RespuestaAutorizacionComprobante->autorizaciones->autorizacion as $autorizacion) {
        echo "Estado: " . $autorizacion->estado . PHP_EOL;
        echo "Fecha Autorización: " . $autorizacion->fechaAutorizacion . PHP_EOL;
        echo "Comprobante XML:
" . $autorizacion->comprobante . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error al autorizar comprobante: " . $e->getMessage();
}
?>
