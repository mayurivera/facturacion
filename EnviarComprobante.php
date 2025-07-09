<?php
// EnviarComprobante.php
// Requiere que el XML ya esté firmado digitalmente (no incluido en este script)

// Ruta al XML firmado
$xmlFirmadoPath = "factura_firmada.xml";
$xml = file_get_contents($xmlFirmadoPath);

// Endpoint de pruebas del SRI
$wsdl = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl";

try {
    $client = new SoapClient($wsdl);
    $params = array("comprobante" => $xml);
    $response = $client->__soapCall("validarComprobante", array($params));

    echo "Estado: " . $response->RespuestaRecepcionComprobante->estado . PHP_EOL;
    if (isset($response->RespuestaRecepcionComprobante->comprobantes)) {
        foreach ($response->RespuestaRecepcionComprobante->comprobantes->comprobante as $comp) {
            echo "Mensaje: " . $comp->mensajes->mensaje->mensaje . PHP_EOL;
            echo "Información Adicional: " . $comp->mensajes->mensaje->informacionAdicional . PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo "Error al enviar comprobante: " . $e->getMessage();
}
?>
