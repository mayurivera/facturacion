<?php
// Datos
$rutaCertificado = '/assets/doc/certificado/6069924_identity.p12';
$claveCertificado = 'Vini1985258';
$archivoXML = 'assets/doc/facturas_generadas/factura_0107202501179999999900120020030000000011546174717.xml';
$archivoXMLFirmado = 'assets/doc/facturas_firmadas/factura_firmada_0107202501179999999900120020030000000011546174717.xml';

// Ejecutar el script Python
$comando = "python3.10 firmar_xml.py $rutaCertificado $claveCertificado $archivoXML $archivoXMLFirmado";
exec($comando, $output, $return_var);

if ($return_var === 0) {
    echo "Firma exitosa: $archivoXMLFirmado\n";
} else {
    echo "Error al firmar el XML:\n";
    print_r($output);
}
?>