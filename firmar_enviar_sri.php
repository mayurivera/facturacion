<?php
include_once 'cx/f_cx.php';
include_once 'assets/lib/firmar-y-enviar-xml-factura-electronica-ecuador-SRI/ejecutar.php';

// Ambiente SRI: 1 = pruebas, 2 = producción
define('AMBIENTE_SRI', 1);  // Cambiar a 2 para producción

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

// 1. Obtener parámetros SRI
$param = ejecutarConsultaSegura("SELECT * FROM parametros_sri WHERE estado_registro = 'activo' LIMIT 1", [], true);

// 2. Consultar datos de establecimiento y punto de emisión
$establecimiento = ejecutarConsultaSegura(
    "SELECT * FROM establecimientos WHERE codigo = ? AND estado = 'activo' LIMIT 1",
    [$input['establecimiento']],
    true
);
$puntoEmision = ejecutarConsultaSegura(
    "SELECT * FROM puntos_emision WHERE codigo = ? AND id_establecimiento = ? AND estado = 'activo' LIMIT 1",
    [$input['puntoEmision'], $establecimiento['id_establecimiento']],
    true
);

// 3. Consultar datos adicionales de cliente si es necesario
$clienteDB = ejecutarConsultaSegura(
    "SELECT razon_social, ruc_cedula, tipo_identificacion, direccion, telefono, correo FROM clientes WHERE ruc_cedula = ? AND estado_registro = 'activo' LIMIT 1",
    [$input['identificacion']],
    true
);

// Obtener el tipo de identificación desde la tabla tipos_identificacion_comprador
$tipoIdentificacionCodigo = '';
if (!empty($clienteDB['tipo_identificacion'])) {
    $tipoIdentificacionRow = ejecutarConsultaSegura(
        "SELECT codigo FROM tipos_identificacion_comprador WHERE descripcion = ? AND estado = 'activo' LIMIT 1",
        [$clienteDB['tipo_identificacion']],
        true
    );
    if ($tipoIdentificacionRow && !empty($tipoIdentificacionRow['codigo'])) {
        $tipoIdentificacionCodigo = $tipoIdentificacionRow['codigo'];
    }
}

// 4. Obtener la dirección de la matriz
$sql = "SELECT direccion FROM establecimientos WHERE matriz = 1 AND estado = 'activo' LIMIT 1";
$resultado = ejecutarConsultaSegura($sql, [], true);
$dirMatriz = $resultado ? $resultado['direccion'] : '';

// 5. Obtener la dirección de establecimiento
$establecimiento = ejecutarConsultaSegura(
    "SELECT * FROM establecimientos WHERE id_establecimiento = ? AND estado = 'activo' LIMIT 1",
    [$input['establecimiento']],
    true
);

// 6. Antes de recorrer los productos, consulta todas las tarifas activas:
$ivaTarifas = [];
$resTarifas = ejecutarConsultaSegura("SELECT codigo_iva, porcentaje FROM iva_tarifas WHERE estado='activo'", [], false);
if ($resTarifas && is_array($resTarifas)) {
    foreach ($resTarifas as $row) {
        $ivaTarifas[floatval($row['porcentaje'])] = $row['codigo_iva'];
    }
}

function generarClaveAcceso($fecha, $tipoComprobante, $ruc, $establecimiento, $puntoEmision, $secuencial, $codigoNumerico, $tipoEmision = '1') {
    // 1. Formatear fecha (dd/mm/yyyy → ddmmyyyy)
    if (strpos($fecha, '/') !== false) {
        $fecha = str_replace('/', '', $fecha);
    } else {
        $fecha = date('dmY', strtotime($fecha));
    }

    // 2. Preparar cada campo
    $tipoComprobante = str_pad($tipoComprobante, 2, '0', STR_PAD_LEFT);
    $ruc = str_pad($ruc, 13, '0', STR_PAD_LEFT);
    $ambiente = (string)AMBIENTE_SRI;
    $serie = str_pad($establecimiento, 3, '0', STR_PAD_LEFT) . str_pad($puntoEmision, 3, '0', STR_PAD_LEFT);
    $secuencial = str_pad($secuencial, 9, '0', STR_PAD_LEFT);
    $codigoNumerico = str_pad($codigoNumerico, 8, '0', STR_PAD_LEFT);
    $tipoEmision = (string)$tipoEmision;

    // 3. Concatenar sin dígito verificador
    $clave = $fecha . $tipoComprobante . $ruc . $ambiente . $serie . $secuencial . $codigoNumerico . $tipoEmision;

    // 4. Calcular dígito verificador (módulo 11)
    $digito = modulo11($clave);

    // 5. Concatenar clave final
    return $clave . $digito;
}

// Cálculo de dígito verificador (algoritmo módulo 11)
function modulo11($clave) {
    $baseMultiplicador = 7;
    $suma = 0;
    $multiplicador = 2;

    for ($i = strlen($clave) - 1; $i >= 0; $i--) {
        $suma += $multiplicador * intval($clave[$i]);
        $multiplicador = ($multiplicador < $baseMultiplicador) ? $multiplicador + 1 : 2;
    }

    $modulo11 = 11 - ($suma % 11);
    if ($modulo11 == 11) return 0;
    if ($modulo11 == 10) return 1;
    return $modulo11;
}

// 5. Generar y guardar el XML de factura
function generarXMLFactura($input, $param, $establecimiento, $puntoEmision, $ruta_xml, $camposAdicionalesDB = [], $clienteDB = [], $dirMatriz = '') {
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;

    // Nodo raíz <factura>
    $factura = $xml->createElement('factura');
    $factura->setAttribute('id', 'comprobante');
    $factura->setAttribute('version', '1.1.0');
    $xml->appendChild($factura);

    // infoTributaria
    $infoTributaria = $xml->createElement('infoTributaria');
    $infoTributaria->appendChild($xml->createElement('ambiente', AMBIENTE_SRI));
    $infoTributaria->appendChild($xml->createElement('tipoEmision', '1'));
    $infoTributaria->appendChild($xml->createElement('razonSocial', $param['razon_social'] ?? ''));
    $infoTributaria->appendChild($xml->createElement('nombreComercial', $param['nombre_comercial'] ?? ''));
    $infoTributaria->appendChild($xml->createElement('ruc', $param['ruc_emisor'] ?? $param['ruc'] ?? ''));
    $infoTributaria->appendChild($xml->createElement('claveAcceso', $input['claveAcceso'] ?? ''));
    $infoTributaria->appendChild($xml->createElement('codDoc', '01'));
    $infoTributaria->appendChild($xml->createElement('estab', str_pad($input['establecimiento'], 3, '0', STR_PAD_LEFT)));
    $infoTributaria->appendChild($xml->createElement('ptoEmi', str_pad($input['puntoEmision'], 3, '0', STR_PAD_LEFT)));
    $infoTributaria->appendChild($xml->createElement('secuencial', str_pad($input['secuencial'] ?? '1', 9, '0', STR_PAD_LEFT)));
    $infoTributaria->appendChild($xml->createElement('dirMatriz', $dirMatriz));
    $factura->appendChild($infoTributaria);

    // infoFactura
    $infoFactura = $xml->createElement('infoFactura');
    $fecha = $input['fechaEmision'] ?? date('d/m/Y');
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        $fecha = date('d/m/Y', strtotime($fecha));
    }
    $infoFactura->appendChild($xml->createElement('fechaEmision', $fecha));
    $infoFactura->appendChild($xml->createElement('dirEstablecimiento', $establecimiento['direccion'] ?? $param['dir_establecimiento'] ?? ''));
    $infoFactura->appendChild($xml->createElement('obligadoContabilidad', strtoupper($param['obligado_contabilidad'] ?? 'NO')));

    // tipoIdentificación del comprador
    $identificacionComprador = $clienteDB['ruc_cedula'] ?? $input['identificacion'] ?? '';
    $tipoId = '';
    if (preg_match('/^\d{13}$/', $identificacionComprador) && substr($identificacionComprador, -3) === '001') {
        $tipoId = '01';
    } elseif (preg_match('/^\d{13}$/', $identificacionComprador)) {
        $tipoId = '01';
    } elseif (preg_match('/^\d{10}$/', $identificacionComprador)) {
        $tipoId = '02';
    } else {
        $tipoId = '07';
    }
    $infoFactura->appendChild($xml->createElement('tipoIdentificacionComprador', $tipoId));
    $infoFactura->appendChild($xml->createElement('razonSocialComprador', $clienteDB['razon_social'] ?? $input['razonSocial'] ?? ''));
    $infoFactura->appendChild($xml->createElement('identificacionComprador', $identificacionComprador));
    $infoFactura->appendChild($xml->createElement('direccionComprador', $clienteDB['direccion'] ?? $input['direccion'] ?? ''));

    $formatNumber = fn($num) => number_format(floatval($num), 2, '.', '');
    $infoFactura->appendChild($xml->createElement('totalSinImpuestos', $formatNumber($input['totales']['subtotalSinImpuestos'] ?? 0)));
    $infoFactura->appendChild($xml->createElement('totalDescuento', $formatNumber($input['totales']['totalDescuento'] ?? 0)));

    // totalConImpuestos
    $totalConImpuestos = $xml->createElement('totalConImpuestos');
    if (!empty($input['totales']['impuestos']) && is_array($input['totales']['impuestos'])) {
        foreach ($input['totales']['impuestos'] as $imp) {
            $totalImpuesto = $xml->createElement('totalImpuesto');
            $totalImpuesto->appendChild($xml->createElement('codigo', '2'));
            $totalImpuesto->appendChild($xml->createElement('codigoPorcentaje', '4'));
            $totalImpuesto->appendChild($xml->createElement('baseImponible', $formatNumber($imp['baseImponible'])));
            $totalImpuesto->appendChild($xml->createElement('valor', $formatNumber($imp['valor'])));
            if (isset($imp['tarifa'])) {
                $totalImpuesto->appendChild($xml->createElement('tarifa', $formatNumber($imp['tarifa'])));
            }
            $totalConImpuestos->appendChild($totalImpuesto);
        }
    }
    $infoFactura->appendChild($totalConImpuestos);
    $infoFactura->appendChild($xml->createElement('propina', $formatNumber($input['totales']['valorPropina'] ?? 0)));
    $infoFactura->appendChild($xml->createElement('importeTotal', $formatNumber($input['totales']['valorTotal'] ?? 0)));
    $infoFactura->appendChild($xml->createElement('moneda', 'DOLAR'));

    // pagos
    $pagos = $xml->createElement('pagos');
    if (!empty($input['formasPago']) && is_array($input['formasPago'])) {
        foreach ($input['formasPago'] as $pago) {
            $pagoNode = $xml->createElement('pago');
            $pagoNode->appendChild($xml->createElement('formaPago', $pago['codigo'] ?? '01'));
            $pagoNode->appendChild($xml->createElement('total', $formatNumber($pago['valor'] ?? 0)));
            if (!empty($pago['plazo'])) {
                $pagoNode->appendChild($xml->createElement('plazo', $pago['plazo']));
                $pagoNode->appendChild($xml->createElement('unidadTiempo', $pago['unidadTiempo'] ?? 'Días'));
            }
            $pagos->appendChild($pagoNode);
        }
    } else {
        $pagoNode = $xml->createElement('pago');
        $pagoNode->appendChild($xml->createElement('formaPago', '01'));
        $pagoNode->appendChild($xml->createElement('total', $formatNumber($input['totales']['valorTotal'] ?? 0)));
        $pagos->appendChild($pagoNode);
    }
    $infoFactura->appendChild($pagos);
    $factura->appendChild($infoFactura);

    // detalles
    $detalles = $xml->createElement('detalles');
    foreach ($input['productos'] as $prod) {
        $detalle = $xml->createElement('detalle');
        $detalle->appendChild($xml->createElement('codigoPrincipal', $prod['codigo'] ?? ''));
        if (!empty($prod['codigoAuxiliar'])) {
            $detalle->appendChild($xml->createElement('codigoAuxiliar', $prod['codigoAuxiliar']));
        }
        $detalle->appendChild($xml->createElement('descripcion', $prod['descripcion'] ?? ''));
        $detalle->appendChild($xml->createElement('cantidad', $formatNumber($prod['cantidad'] ?? 0)));
        $detalle->appendChild($xml->createElement('precioUnitario', $formatNumber($prod['precio_unitario'] ?? 0)));
        $detalle->appendChild($xml->createElement('descuento', $formatNumber($prod['descuento'] ?? 0)));
        $detalle->appendChild($xml->createElement('precioTotalSinImpuesto', $formatNumber($prod['subtotal'] ?? 0)));

        // detallesAdicionales
        if (!empty($prod['detallesAdicionales']) && is_array($prod['detallesAdicionales'])) {
            $detallesAdicionales = $xml->createElement('detallesAdicionales');
            foreach ($prod['detallesAdicionales'] as $da) {
                $detAdicional = $xml->createElement('detAdicional');
                $detAdicional->setAttribute('nombre', $da['nombre']);
                $detAdicional->setAttribute('valor', $da['valor']);
                $detallesAdicionales->appendChild($detAdicional);
            }
            $detalle->appendChild($detallesAdicionales);
        }

        // impuestos
        $impuestos = $xml->createElement('impuestos');
        $ivaRate = 15;
        $codigoPorcentaje = '4';
        $tarifa = $ivaRate;
        $valor = ($prod['subtotal'] ?? 0) * ($ivaRate / 100);

        $impuesto = $xml->createElement('impuesto');
        $impuesto->appendChild($xml->createElement('codigo', '2'));
        $impuesto->appendChild($xml->createElement('codigoPorcentaje', $codigoPorcentaje));
        $impuesto->appendChild($xml->createElement('tarifa', $formatNumber($tarifa)));
        $impuesto->appendChild($xml->createElement('baseImponible', $formatNumber($prod['subtotal'] ?? 0)));
        $impuesto->appendChild($xml->createElement('valor', $formatNumber($valor)));
        $impuestos->appendChild($impuesto);
        $detalle->appendChild($impuestos);

        $detalles->appendChild($detalle);
    }
    $factura->appendChild($detalles);

    // infoAdicional
    $infoAdicionalCampos = [];
    if (!empty($input['camposAdicionales'])) {
        foreach ($input['camposAdicionales'] as $campo) {
            $infoAdicionalCampos[$campo['nombre']] = $campo['valor'];
        }
    }
    foreach ($camposAdicionalesDB as $campo) {
        $infoAdicionalCampos[$campo['nombre']] = $campo['valor'];
    }
    if (!empty($clienteDB['telefono'])) {
        $infoAdicionalCampos['Teléfono'] = $clienteDB['telefono'];
    }
    if (!empty($clienteDB['correo'])) {
        $infoAdicionalCampos['Email'] = $clienteDB['correo'];
    }

    if (!empty($infoAdicionalCampos)) {
        $infoAdicional = $xml->createElement('infoAdicional');
        foreach ($infoAdicionalCampos as $nombre => $valor) {
            $campoAdicional = $xml->createElement('campoAdicional', $valor);
            $campoAdicional->setAttribute('nombre', $nombre);
            $infoAdicional->appendChild($campoAdicional);
        }
        $factura->appendChild($infoAdicional);
    }

    // Guardar XML
    $dir = dirname($ruta_xml);
    if (!is_dir($dir)) {
        error_log("Directorio no existe: $dir");
        return false;
    }
    if (!is_writable($dir)) {
        error_log("No hay permisos de escritura en: $dir");
        return false;
    }

    $resultado = $xml->save($ruta_xml);
    return $resultado !== false ? basename($ruta_xml) : false;
}

// --- Configura rutas ---
// Generar nombre único para el XML
$codigoNumerico = rand(10000000, 99999999); // o puedes usar un secuencial fijo
$tipoEmision = '1'; // normalmente 1 para emisión normal

$secuencial = $input['secuencial'] ?? '000000001';
$fechaEmision = $input['fechaEmision'] ?? date('d/m/Y');

$claveAcceso = generarClaveAcceso(
    $fechaEmision,
    '01', // tipo comprobante
    $param['ruc_emisor'] ?? $param['ruc'] ?? '',
    AMBIENTE_SRI,
    $input['establecimiento'],
    $input['puntoEmision'],
    $secuencial,
    $codigoNumerico,
    $tipoEmision
);

// ASIGNA la clave al input antes de generar el XML
$input['claveAcceso'] = $claveAcceso;

$fecha = date('Ymd_His');
$nombre_xml = $claveAcceso
    ? "factura_{$claveAcceso}.xml"
    : "factura_{$secuencial}_{$fecha}.xml";

$ruta_factura = __DIR__ . "/assets/doc/facturas_generadas/{$nombre_xml}";
$ruta_certificado = __DIR__ . '/assets/doc/certificado/mi_certificado.p12';
$clave = $input['claveFirma'];
$ruta_respuesta = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// 5. Generar y guardar el XML de factura (ahora sí, con la ruta correcta)
$archivoXML = generarXMLFactura($input, $param, $establecimiento, $puntoEmision, $ruta_factura, $camposAdicionalesDB, $clienteDB, $dirMatriz);

// --- Ejecutar firma del XML usando Python y el entorno virtual ---

// Define las rutas absolutas
$rutaCertificado = __DIR__ . '/assets/doc/certificado/6069924_identity.p12';
$claveCertificado = 'Vini1985258';
$archivoXML_SF = __DIR__ . "/assets/doc/facturas_generadas/$archivoXML";
$archivoXMLFirmado = __DIR__ . "/assets/doc/facturas_firmadas/$archivoXML";

$comando = "python3.10 firmar_xml.py \"$rutaCertificado\" \"$claveCertificado\" \"$archivoXML_SF\" \"$archivoXMLFirmado\"";
exec($comando, $output, $return_var);

// Verifica si el archivo firmado existe
if (file_exists($archivoXMLFirmado)) {
    echo json_encode([
        'success' => true,
        'message' => 'XML firmado correctamente.',
        'archivoFirmado' => $archivoXMLFirmado,
        'output' => $output // opcional, útil para debugging
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al firmar el XML. Archivo no encontrado.',
        'output' => $output
    ]);
}

// URL del SRI para envío y validación
if (AMBIENTE_SRI == 1) {
    // Ambiente pruebas
    $urlEnvioSRI = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
    $urlValidacionSRI = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
} else {
    // Ambiente producción
    $urlEnvioSRI = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
    $urlValidacionSRI = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
}

function enviarComprobanteAlSRI($rutaXmlFirmado, $recepcionWsdl, $autorizacionWsdl) {
    $xml = file_get_contents($rutaXmlFirmado);
    $comprobanteBase64 = base64_encode($xml);
    $ambienteTag = "PRUEBAS";

    try {
        // 1. Enviar comprobante firmado a recepción
        $clientRecepcion = new SoapClient($recepcionWsdl, ['cache_wsdl' => WSDL_CACHE_NONE]);
        $params = ['comprobante' => $comprobanteBase64];

        $respuestaRecepcion = $clientRecepcion->__soapCall("validarComprobante", [$params]);
        $estadoRecepcion = $respuestaRecepcion->RespuestaRecepcionComprobante->estado;

        if ($estadoRecepcion === 'RECIBIDA') {
            // 2. Extraer clave de acceso desde XML firmado
            $xmlObj = new SimpleXMLElement($xml);
            $claveAcceso = (string)$xmlObj->infoTributaria->claveAcceso;

            // 3. Esperar unos segundos antes de consultar autorización
            sleep(2);

            // 4. Consultar autorización
            $clientAutorizacion = new SoapClient($autorizacionWsdl, ['cache_wsdl' => WSDL_CACHE_NONE]);
            $respuestaAutorizacion = $clientAutorizacion->__soapCall("autorizacionComprobante", [
                ["claveAccesoComprobante" => $claveAcceso]
            ]);

            if (isset($respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion)) {
                $autorizacion = $respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion;

                $estado = $autorizacion->estado;
                $numeroAutorizacion = $autorizacion->numeroAutorizacion;
                $fechaAutorizacion = $autorizacion->fechaAutorizacion;
                $xmlAutorizado = $autorizacion->comprobante;

                // 5. Guardar XML autorizado con estructura completa
                $autorizadoFinal = "<?xml version='1.0' encoding='UTF-8'?>\n";
                $autorizadoFinal .= "<autorizacion>\n";
                $autorizadoFinal .= "<estado>$estado</estado>\n";
                $autorizadoFinal .= "<numeroAutorizacion>$numeroAutorizacion</numeroAutorizacion>\n";
                $autorizadoFinal .= "<fechaAutorizacion>$fechaAutorizacion</fechaAutorizacion>\n";
                $autorizadoFinal .= "<comprobante><![CDATA[$xmlAutorizado]]></comprobante>\n";
                $autorizadoFinal .= "<ambiente>$ambienteTag</ambiente>\n";
                $autorizadoFinal .= "</autorizacion>";

                $rutaGuardado = __DIR__ . "/assets/doc/facturas_autorizadas/{$claveAcceso}-autorizado.xml";
                file_put_contents($rutaGuardado, $autorizadoFinal);

                return [
                    'success' => true,
                    'mensaje' => "AUTORIZADO - Número: $numeroAutorizacion",
                    'estado' => $estado,
                    'autorizacion' => $numeroAutorizacion,
                    'rutaAutorizado' => $rutaGuardado
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => "NO AUTORIZADO: El comprobante no tiene autorización."
                ];
            }
        } else {
            return [
                'success' => false,
                'mensaje' => "RECHAZADO por recepción SRI: $estadoRecepcion"
            ];
        }

    } catch (Exception $e) {
        return [
            'success' => false,
            'mensaje' => "Error al enviar al SRI: " . $e->getMessage()
        ];
    }
}


// Ejecutar desde CLI o incluir desde otro archivo
$res = enviarComprobanteAlSRI($archivoXMLFirmado,$urlEnvioSRI,$urlValidacionSRI); // Usa 'PRUEBAS' o 'PRODUCCION' desde tus parámetros
if ($res['success']) {
    echo "✔️ " . $res['mensaje'];
} else {
    echo "❌ " . $res['mensaje'];
}

?>