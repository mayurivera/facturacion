<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'header.php'; ?>
<body>
    <div class="main-container">
        <h1 class="page-title">Factura</h1>
        <?php include_once "cx/f_cx.php"; ?> 
        <form id="formFactura" class="container mt-3">
            <style>
            .etiqueta-obligatoria::after { content: ' *'; color: red; }
            .divisor { border-top: 1px solid #ccc; margin: 15px 0; }
            .totales-panel .total-label { font-weight: bold; }
            .totales-panel .total-value { text-align: right; }
            .total-final { background-color: #f8f9fa; font-size: 1.1em; }
            .total-final .total-value { font-size: 1.2em; color: #28a745; }
            </style>
            <div class="main-container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">Emisor</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="establecimiento" class="form-label etiqueta-obligatoria">Establecimiento:</label>
                                        <select id="establecimiento" class="form-select">
                                            <option value="">Cargando establecimientos...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="puntoEmision" class="form-label etiqueta-obligatoria">Punto de Emisión:</label>
                                        <select id="puntoEmision" class="form-select" disabled>
                                            <option value="">Seleccione un establecimiento</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fechaEmision" class="form-label">Fecha de Emisión:</label>
                                        <input type="date" id="fechaEmision" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="guiaRemision" class="form-label">Guía de Remisión:</label>
                                        <input type="text" id="guiaRemision" class="form-control" placeholder="001-001-000000000">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">Detalle de la Factura</div>
                            <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBuscarProducto">
                                <i class="fa fa-plus"></i> Añadir Producto
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                    <th style="width: 10%;">Código</th>
                                    <th style="width: 30%;">Descripción</th>
                                    <th style="width: 10%;">Cant.</th>
                                    <th style="width: 15%;">Precio Unit.</th>
                                    <th style="width: 15%;">Descuento</th>
                                    <th style="width: 15%;">Subtotal</th>
                                    <th style="width: 10%;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpo-tabla-productos"></tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">Adquirente</div>
                            <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                <label for="tipoIdentificacion" class="form-label etiqueta-obligatoria">Tipo Identificación:</label>
                                <input type="text" id="tipoIdentificacion" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="identificacion" class="form-label etiqueta-obligatoria">Identificación:</label>
                                <div class="input-group">
                                    <input type="text" id="identificacion" class="form-control" placeholder="RUC / CI / Pasaporte">
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarCliente"><i class="fa fa-search"></i></button>
                                </div>
                                <div id="mensajeCliente" class="mt-2" style="display: none;"></div>
                                </div>
                                <div class="col-md-12 mb-3">
                                <label for="razonSocial" class="form-label etiqueta-obligatoria">Razón Social:</label>
                                <input type="text" id="razonSocial" class="form-control" readonly>
                                </div>
                                <div class="col-md-12 mb-3">
                                <label for="direccion" class="form-label">Dirección:</label>
                                <input type="text" id="direccion" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="tel" id="telefono" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                <label for="email" class="form-label etiqueta-obligatoria">Correo electrónico:</label>
                                <input type="email" id="email" class="form-control" readonly>
                                <small class="form-text text-muted">Asegúrese de que el correo sea correcto para la entrega del comprobante.</small>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">Formas de Pago</div>
                            <div class="card-body">
                            <div id="lista-formas-pago"></div>
                            <div class="divisor"></div>
                            <button type="button" class="btn btn-outline-success btn-sm btn-forma-pago" data-bs-toggle="modal" data-bs-target="#modalFormaPago">
                                <i class="fa-solid fa-plus"></i> Añadir Forma de Pago
                            </button>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">Campos Adicionales</div>
                            <div class="card-body">
                            <div id="lista-campos-adicionales"></div>
                            <div class="divisor"></div>
                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalCampoAdicional">
                                <i class="fa-solid fa-plus"></i> Añadir Campo Adicional
                            </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card totales-panel">
                        <div class="card-header bg-success text-white">Totales</div>
                        <div class="card-body">
                        <table class="table">
                            <tbody>
                            <tr>
                                <td class="total-label">Subtotal 15%:</td>
                                <td class="total-value" id="subtotal15">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">Subtotal 5%:</td>
                                <td class="total-value" id="subtotal5">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">Subtotal 0%:</td>
                                <td class="total-value" id="subtotal0">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">Subtotal no objeto de IVA:</td>
                                <td class="total-value" id="subtotalNoIva">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">Subtotal exento de IVA:</td>
                                <td class="total-value" id="subtotalExentoIva">0.00</td>
                            </tr>
                            <tr class="table-group-divider">
                                <td class="total-label">Subtotal sin impuestos:</td>
                                <td class="total-value" id="subtotalSinImpuestos">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">Total Descuento:</td>
                                <td class="total-value" id="totalDescuento">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">Valor ICE:</td>
                                <td class="total-value" id="valorICE">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">IVA 15%:</td>
                                <td class="total-value" id="valorIva15">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">IVA 5%:</td>
                                <td class="total-value" id="valorIva5">0.00</td>
                            </tr>
                            <tr>
                                <td class="total-label">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="checkPropina">
                                    <label class="form-check-label" for="checkPropina">Propina (10%):</label>
                                </div>
                                </td>
                                <td class="total-value" id="valorPropina">0.00</td>
                            </tr>
                            <tr class="table-group-divider total-final">
                                <td class="total-label">VALOR TOTAL:</td>
                                <td class="total-value" id="valorTotal">0.00</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <button type="button" class="btn btn-secondary" id="btnSoloGuardar"><i class="fa-solid fa-floppy-disk"></i> Guardar sin Firmar</button>
                    <input type="submit" class="btn btn-success" value="&#9998; Firmar y Enviar">
                </div>
            </div>

            <!-- Modal Productos -->
            <div class="modal fade" id="modalBuscarProducto" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Listado de Productos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                        <input type="text" id="inputBuscarProducto" class="form-control" placeholder="Buscar producto por código o descripción...">
                        </div>
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="width: 15%;">Código</th>
                                <th style="width: 40%;">Descripción</th>
                                <th style="width: 15%;">Precio Unit.</th>
                                <th style="width: 20%;">Acción</th>
                            </tr>
                            </thead>
                            <tbody id="tablaModalProductos"></tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Modal Forma de Pago -->
            <div class="modal fade" id="modalFormaPago" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Añadir Forma de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formaPagoSelect" class="form-label">Forma de Pago</label>
                            <select id="formaPagoSelect" class="form-select">
                                <!-- Opciones se cargan dinámicamente -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formaPagoValor" class="form-label">Valor</label>
                            <input type="number" id="formaPagoValor" class="form-control" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnGuardarFormaPago">Guardar</button>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Modal Campo Adicional -->
            <div class="modal fade" id="modalCampoAdicional" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Añadir Campo Adicional</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                        <label for="campoAdicionalNombre" class="form-label">Nombre</label>
                        <input type="text" id="campoAdicionalNombre" class="form-control" placeholder="Ej: Correo Adicional">
                        </div>
                        <div class="mb-3">
                        <label for="campoAdicionalValor" class="form-label">Valor</label>
                        <input type="text" id="campoAdicionalValor" class="form-control" placeholder="Ej: info@empresa.com">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnGuardarCampoAdicional">Guardar</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Modal de Firma (fuera del formFactura) -->
        <div class="modal fade" id="modalFirma" tabindex="-1" aria-labelledby="modalFirmaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="formFirma" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFirmaLabel">Firmar Factura Electrónica</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="claveFirma" class="form-label">Contraseña de Firma Digital</label>
                    <input type="password" class="form-control" id="claveFirma" required autocomplete="off">
                    </div>
                    <div id="firmaMensaje" class="alert d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Firmar y Enviar al SRI</button>
                </div>
                </form>
            </div>
        </div>

        <script>

            document.getElementById('formFactura').addEventListener('submit', function(e) {
                e.preventDefault();

                // Validación de bloques requeridos
                const establecimiento = document.getElementById('establecimiento').value.trim();
                const puntoEmision = document.getElementById('puntoEmision').value.trim();
                const fechaEmision = document.getElementById('fechaEmision').value.trim();
                const tipoIdentificacion = document.getElementById('tipoIdentificacion').value.trim();
                const identificacion = document.getElementById('identificacion').value.trim();
                const razonSocial = document.getElementById('razonSocial').value.trim();
                const email = document.getElementById('email').value.trim();

                // Detalle de la factura
                const productos = document.querySelectorAll('#cuerpo-tabla-productos tr');
                // Totales
                const valorTotal = parseFloat(document.getElementById('valorTotal').textContent) || 0;
                // Formas de pago
                const formasPago = document.querySelectorAll('#lista-formas-pago > div');

                let mensaje = '';
                if (!establecimiento) mensaje += 'Seleccione un establecimiento.\n';
                if (!puntoEmision) mensaje += 'Seleccione un punto de emisión.\n';
                if (!fechaEmision) mensaje += 'Ingrese la fecha de emisión.\n';
                if (!tipoIdentificacion) mensaje += 'Ingrese el tipo de identificación del adquirente.\n';
                if (!identificacion) mensaje += 'Ingrese la identificación del adquirente.\n';
                if (!razonSocial) mensaje += 'Ingrese la razón social del adquirente.\n';
                if (!email) mensaje += 'Ingrese el correo electrónico del adquirente.\n';
                if (productos.length === 0) mensaje += 'Agregue al menos un producto al detalle de la factura.\n';
                if (valorTotal <= 0) mensaje += 'El valor total debe ser mayor a cero.\n';
                if (formasPago.length === 0) mensaje += 'Agregue al menos una forma de pago.\n';

                if (mensaje) {
                    alert('Faltan campos por completar:\n\n' + mensaje);
                    return;
                }

                // Si todo está correcto, mostrar modal de firma
                const modalFirma = new bootstrap.Modal(document.getElementById('modalFirma'));
                modalFirma.show();
            });

            document.getElementById("formFirma").addEventListener("submit", function (e) {
                e.preventDefault();

                const claveFirma = document.getElementById("claveFirma").value;
                const mensajeDiv = document.getElementById("firmaMensaje");

                // Limpia y oculta el mensaje anterior
                mensajeDiv.classList.add("d-none");
                mensajeDiv.classList.remove("alert-success", "alert-danger");
                mensajeDiv.textContent = "";

                const datosFactura = {
                    ...datosDeLaFactura, // Asegúrate de construir tu objeto con todos los datos necesarios
                    claveFirma: claveFirma
                };

                fetch("firmar_y_enviar.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datosFactura)
                })
                .then(res => res.json())
                .then(res => {
                    mensajeDiv.classList.remove("d-none");
                    if (res.success) {
                        mensajeDiv.classList.add("alert-success");
                        mensajeDiv.textContent = res.message || "Firma exitosa.";
                    } else {
                        mensajeDiv.classList.add("alert-danger");
                        mensajeDiv.textContent = res.message || "Error al firmar la factura.";
                        if (res.output && res.output.length > 0) {
                            mensajeDiv.textContent += "\n" + res.output.join("\n");
                        }
                    }
                })
                .catch(err => {
                    mensajeDiv.classList.remove("d-none");
                    mensajeDiv.classList.add("alert-danger");
                    mensajeDiv.textContent = "Error inesperado: " + err.message;
                });
            });

            function cargarFormasPago() {
                fetch('ajax/formas_pago.php')
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('formaPagoSelect');
                        select.innerHTML = ''; // Limpiar opciones anteriores

                        // Agregar una opción por cada forma de pago
                        data.forEach(forma => {
                            const option = document.createElement('option');
                            option.value = forma.codigo_forma_pago;
                            option.textContent = forma.descripcion;
                            select.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar formas de pago:', error);
                    });
            }

            // Llama esta función cuando se abra el modal
            document.getElementById('modalFormaPago').addEventListener('show.bs.modal', cargarFormasPago);

            function obtenerDatosFactura() {
                // Emisor
                const establecimiento = document.getElementById('establecimiento').value;
                const puntoEmision = document.getElementById('puntoEmision').value;
                const fechaEmision = document.getElementById('fechaEmision').value;
                const guiaRemision = document.getElementById('guiaRemision').value;

                // Adquirente
                const tipoIdentificacion = document.getElementById('tipoIdentificacion').value;
                const identificacion = document.getElementById('identificacion').value;
                const razonSocial = document.getElementById('razonSocial').value;
                const direccion = document.getElementById('direccion').value;
                const telefono = document.getElementById('telefono').value;
                const email = document.getElementById('email').value;

                // Productos (detalles)
                const productos = [];
                document.querySelectorAll('#cuerpo-tabla-productos tr').forEach(row => {
                    // Puedes agregar inputs ocultos o atributos data-* para estos campos si no están en la tabla
                    const detallesAdicionales = [];
                    // Ejemplo: busca detalles adicionales por producto si existen
                    // row.querySelectorAll('.detalle-adicional').forEach(da => {
                    //     detallesAdicionales.push({ nombre: da.dataset.nombre, valor: da.dataset.valor });
                    // });

                    // Impuestos por producto (ajusta según tu lógica)
                    const impuestos = [];
                    // Ejemplo: si tienes varios impuestos por producto, agrégalos aquí
                    // impuestos.push({codigo: "2", codigoPorcentaje: "2", tarifa: "12", baseImponible: "100.00", valor: "12.00"});

                    productos.push({
                        id: row.getAttribute('data-id'),
                        codigo: row.children[0].textContent,
                        codigoAuxiliar: row.getAttribute('data-codigo-auxiliar') || '', // agrega este atributo si lo necesitas
                        descripcion: row.children[1].textContent,
                        cantidad: parseFloat(row.querySelector('.cantidad').value) || 0,
                        precio_unitario: parseFloat(row.querySelector('.precio-unitario').value) || 0,
                        descuento: parseFloat(row.querySelector('.descuento').value) || 0,
                        subtotal: parseFloat(row.querySelector('.subtotal-fila').textContent) || 0,
                        iva: parseFloat(row.getAttribute('data-iva-rate')) || 0,
                        detallesAdicionales: detallesAdicionales,
                        impuestos: impuestos
                    });
                });

                // Formas de pago
                const formasPago = [];
                document.querySelectorAll('#lista-formas-pago > div').forEach(div => {
                    const texto = div.querySelector('span').textContent.trim();
                    const valor = parseFloat(div.querySelectorAll('span')[1].textContent.replace('$', '')) || 0;
                    // Puedes guardar el código de forma de pago si lo tienes (ej: value del select)
                    const codigo = div.getAttribute('data-codigo') || '';
                    const plazo = div.getAttribute('data-plazo') || '';
                    const unidadTiempo = div.getAttribute('data-unidad-tiempo') || '';
                    formasPago.push({ descripcion: texto, valor: valor, codigo, plazo, unidadTiempo });
                });

                // Campos adicionales
                const camposAdicionales = [];
                document.querySelectorAll('#lista-campos-adicionales > div').forEach(div => {
                    const texto = div.querySelector('span').textContent;
                    const partes = texto.split(':');
                    if (partes.length === 2) {
                        camposAdicionales.push({
                            nombre: partes[0].replace('*', '').replace(/\s/g, ''),
                            valor: partes[1].trim()
                        });
                    }
                });

                // Totales e impuestos globales
                const totales = {
                    subtotal15: parseFloat(document.getElementById('subtotal15').textContent) || 0,
                    subtotal5: parseFloat(document.getElementById('subtotal5').textContent) || 0,
                    subtotal0: parseFloat(document.getElementById('subtotal0').textContent) || 0,
                    subtotalNoIva: parseFloat(document.getElementById('subtotalNoIva').textContent) || 0,
                    subtotalExentoIva: parseFloat(document.getElementById('subtotalExentoIva').textContent) || 0,
                    subtotalSinImpuestos: parseFloat(document.getElementById('subtotalSinImpuestos').textContent) || 0,
                    totalDescuento: parseFloat(document.getElementById('totalDescuento').textContent) || 0,
                    valorICE: parseFloat(document.getElementById('valorICE').textContent) || 0,
                    valorIva15: parseFloat(document.getElementById('valorIva15').textContent) || 0,
                    valorIva5: parseFloat(document.getElementById('valorIva5').textContent) || 0,
                    valorPropina: parseFloat(document.getElementById('valorPropina').textContent) || 0,
                    valorTotal: parseFloat(document.getElementById('valorTotal').textContent) || 0,
                    impuestos: []
                };

                // Llenar el array de impuestos para el backend
                if (totales.subtotal15 > 0) {
                    totales.impuestos.push({
                        codigo: "2", // IVA
                        codigoPorcentaje: "2", // IVA 15%
                        baseImponible: totales.subtotal15,
                        valor: totales.valorIva15
                    });
                }
                if (totales.subtotal5 > 0) {
                    totales.impuestos.push({
                        codigo: "2", // IVA
                        codigoPorcentaje: "1", // IVA 5%
                        baseImponible: totales.subtotal5,
                        valor: totales.valorIva5
                    });
                }
                if (totales.subtotal0 > 0) {
                    totales.impuestos.push({
                        codigo: "2", // IVA
                        codigoPorcentaje: "0", // IVA 0%
                        baseImponible: totales.subtotal0,
                        valor: 0
                    });
                }

                return {
                    establecimiento,
                    puntoEmision,
                    fechaEmision,
                    guiaRemision,
                    tipoIdentificacion,
                    identificacion,
                    razonSocial,
                    direccion,
                    telefono,
                    email,
                    productos,
                    formasPago,
                    camposAdicionales,
                    totales
                };
            }

            document.addEventListener('DOMContentLoaded', function () {
                // --- Referencias a elementos ---
                const fechaEmisionInput = document.getElementById('fechaEmision');
                const selectEstablecimiento = document.getElementById('establecimiento');
                const selectPuntoEmision = document.getElementById('puntoEmision');
                const inputBuscar = document.getElementById('inputBuscarProducto');
                const tablaModalProductos = document.getElementById('tablaModalProductos');
                const mensajeClienteDiv = document.getElementById('mensajeCliente');
                const btnBuscarCliente = document.getElementById('btnBuscarCliente');
                const identificacionInput = document.getElementById('identificacion');
                const tipoIdentificacionInput = document.getElementById('tipoIdentificacion');
                const razonSocialInput = document.getElementById('razonSocial');
                const direccionInput = document.getElementById('direccion');
                const telefonoInput = document.getElementById('telefono');
                const emailInput = document.getElementById('email');

                // --- Inicialización de fecha ---
                fechaEmisionInput.valueAsDate = new Date();

                // --- Cargar establecimientos y puntos de emisión ---
                function cargarEstablecimientos() {
                    fetch('cx/api.php?action=getEstablecimientos')
                        .then(response => response.json())
                        .then(data => {
                            selectEstablecimiento.innerHTML = '<option value="">Seleccione</option>';
                            if (data.error) {
                                selectEstablecimiento.innerHTML = '<option value="">Error al cargar</option>';
                                selectEstablecimiento.disabled = true;
                                return;
                            }
                            data.forEach(establecimiento => {
                                const option = document.createElement('option');
                                option.value = establecimiento.id_establecimiento;
                                option.textContent = `${establecimiento.codigo} - ${establecimiento.nombre}`;
                                selectEstablecimiento.appendChild(option);
                            });
                            selectPuntoEmision.innerHTML = '<option value="">Seleccione un establecimiento</option>';
                            selectPuntoEmision.disabled = true;
                            if (data.length === 1) {
                                selectEstablecimiento.value = data[0].id_establecimiento;
                                cargarPuntosEmision(data[0].id_establecimiento);
                            }
                        })
                        .catch(() => {
                            selectEstablecimiento.innerHTML = '<option value="">Error al cargar</option>';
                            selectEstablecimiento.disabled = true;
                        });
                }

                function cargarPuntosEmision(id_establecimiento) {
                    selectPuntoEmision.innerHTML = '<option value="">Cargando puntos de emisión...</option>';
                    selectPuntoEmision.disabled = true;
                    fetch(`cx/api.php?action=getPuntosEmision&id_establecimiento=${id_establecimiento}`)
                        .then(response => response.json())
                        .then(data => {
                            selectPuntoEmision.innerHTML = '<option value="">Seleccione</option>';
                            selectPuntoEmision.disabled = false;
                            if (data.error || data.length === 0) {
                                selectPuntoEmision.innerHTML = '<option value="">No hay puntos de emisión disponibles</option>';
                                selectPuntoEmision.disabled = true;
                                return;
                            }
                            data.forEach(punto => {
                                const option = document.createElement('option');
                                option.value = punto.id_punto;
                                option.textContent = `${punto.codigo} - ${punto.descripcion}`;
                                selectPuntoEmision.appendChild(option);
                            });
                            if (data.length === 1) selectPuntoEmision.value = data[0].id_punto;
                        })
                        .catch(() => {
                            selectPuntoEmision.innerHTML = '<option value="">Error al cargar puntos</option>';
                            selectPuntoEmision.disabled = true;
                        });
                }

                // --- Totales ---
                function calcularTotales() {
                    let subtotales = { 15: 0, 5: 0, 0: 0, noObjeto: 0, exento: 0 };
                    let totalDescuento = 0;
                    document.querySelectorAll('#cuerpo-tabla-productos tr').forEach(row => {
                        const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
                        const precio = parseFloat(row.querySelector('.precio-unitario').value) || 0;
                        const descuento = parseFloat(row.querySelector('.descuento')?.value) || 0;
                        const subtotalFila = (cantidad * precio) - descuento;
                        row.querySelector('.subtotal-fila').textContent = subtotalFila.toFixed(2);
                        totalDescuento += descuento;
                        const ivaRate = parseInt(row.dataset.ivaRate);
                        if ([15, 5, 0].includes(ivaRate)) subtotales[ivaRate] += cantidad * precio;
                    });
                    const subtotalSinImpuestos = subtotales[15] + subtotales[5] + subtotales[0] + subtotales.noObjeto + subtotales.exento;
                    const factorDescuento = subtotalSinImpuestos > 0 ? (totalDescuento / subtotalSinImpuestos) : 0;
                    const baseImponible15 = subtotales[15] * (1 - factorDescuento);
                    const baseImponible5 = subtotales[5] * (1 - factorDescuento);
                    const baseImponible0 = subtotales[0] * (1 - factorDescuento);
                    const baseImponibleNoObjeto = subtotales.noObjeto * (1 - factorDescuento);
                    const baseImponibleExento = subtotales.exento * (1 - factorDescuento);
                    const valorIva15 = baseImponible15 * 0.15;
                    const valorIva5 = baseImponible5 * 0.05;
                    const propina = document.getElementById('checkPropina').checked ? (baseImponible15 + baseImponible5 + baseImponible0) * 0.10 : 0;
                    const valorTotal = (subtotalSinImpuestos - totalDescuento) + valorIva15 + valorIva5 + propina;
                    document.getElementById('subtotal15').textContent = baseImponible15.toFixed(2);
                    document.getElementById('subtotal5').textContent = baseImponible5.toFixed(2);
                    document.getElementById('subtotal0').textContent = baseImponible0.toFixed(2);
                    document.getElementById('subtotalNoIva').textContent = baseImponibleNoObjeto.toFixed(2);
                    document.getElementById('subtotalExentoIva').textContent = baseImponibleExento.toFixed(2);
                    document.getElementById('subtotalSinImpuestos').textContent = (subtotalSinImpuestos - totalDescuento).toFixed(2);
                    document.getElementById('totalDescuento').textContent = totalDescuento.toFixed(2);
                    document.getElementById('valorIva15').textContent = valorIva15.toFixed(2);
                    document.getElementById('valorIva5').textContent = valorIva5.toFixed(2);
                    document.getElementById('valorPropina').textContent = propina.toFixed(2);
                    document.getElementById('valorTotal').textContent = valorTotal.toFixed(2);
                }

                // --- Formas de pago ---
                function agregarFormaPago() {
                    const select = document.getElementById('formaPagoSelect');
                    const texto = select.options[select.selectedIndex].text;
                    const valor = parseFloat(document.getElementById('formaPagoValor').value) || 0;
                    const codigo = select.value; // <-- aquí obtienes el código real
                    if (valor <= 0) { alert('El valor debe ser mayor a cero.'); return; }
                    const div = document.createElement('div');
                    div.className = 'd-flex justify-content-between align-items-center mb-2';
                    div.setAttribute('data-codigo', codigo); // <-- agrega el código aquí
                    div.innerHTML = `<span>${texto}:</span><span>$${valor.toFixed(2)}</span><button type="button" class="btn btn-danger btn-sm eliminar-forma-pago"><i class="fa fa-times"></i></button>`;
                    document.getElementById('lista-formas-pago').appendChild(div);
                    div.querySelector('.eliminar-forma-pago').addEventListener('click', () => div.remove());
                    modalFormaPago.hide();
                    document.getElementById('formaPagoValor').value = '';
                }

                // --- Campos adicionales ---
                function agregarCampoAdicional() {
                    const nombre = document.getElementById('campoAdicionalNombre').value.trim();
                    const valor = document.getElementById('campoAdicionalValor').value.trim();
                    if (!nombre || !valor) { alert('Ambos campos son obligatorios.'); return; }
                    const div = document.createElement('div');
                    div.className = 'd-flex justify-content-between align-items-center mb-1';
                    div.innerHTML = `<span><strong>${nombre}:</strong> ${valor}</span><button class="btn btn-outline-danger btn-sm btn-accion" type="button"><i class="fa fa-times"></i></button>`;
                    div.querySelector('button').addEventListener('click', (e) => e.currentTarget.parentElement.remove());
                    document.getElementById('lista-campos-adicionales').appendChild(div);
                    document.getElementById('campoAdicionalNombre').value = '';
                    document.getElementById('campoAdicionalValor').value = '';
                    modalCampoAdicional.hide();
                }

                // --- Productos ---
                function cargarProductos(search = '') {
                    fetch(`buscar_productos.php?search=${encodeURIComponent(search)}`)
                        .then(res => res.json())
                        .then(data => {
                            tablaModalProductos.innerHTML = '';
                            if (data.productos && data.productos.length > 0) {
                                data.productos.forEach(p => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td>${p.codigo}</td>
                                        <td>${p.nombre}</td>
                                        <td>${parseFloat(p.precio_unitario).toFixed(2)}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm btn-accion"
                                                data-id="${p.id_producto}"
                                                data-codigo="${p.codigo}"
                                                data-descripcion="${p.nombre}"
                                                data-precio="${p.precio_unitario}"
                                                data-iva="${p.tarifa_iva}">
                                                Añadir
                                            </button>
                                        </td>`;
                                    tablaModalProductos.appendChild(row);
                                });
                            } else {
                                tablaModalProductos.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No se encontraron productos.</td></tr>`;
                            }
                        })
                        .catch(() => {
                            tablaModalProductos.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error al cargar productos.</td></tr>`;
                        });
                }

                // --- Cliente ---
                function showClientMessage(message, type = 'alert-info') {
                    mensajeClienteDiv.style.display = 'block';
                    mensajeClienteDiv.className = `alert ${type}`;
                    mensajeClienteDiv.innerHTML = message;
                }
                function hideClientMessage() {
                    mensajeClienteDiv.style.display = 'none';
                    mensajeClienteDiv.innerHTML = '';
                    mensajeClienteDiv.className = '';
                }
                function clearAdquirenteFields() {
                    tipoIdentificacionInput.value = '';
                    razonSocialInput.value = '';
                    direccionInput.value = '';
                    telefonoInput.value = '';
                    emailInput.value = '';
                    hideClientMessage();
                }
                function populateAdquirenteFields(cliente) {
                    if (cliente) {
                        tipoIdentificacionInput.value = cliente.tipo_identificacion || '';
                        razonSocialInput.value = cliente.razon_social || '';
                        direccionInput.value = cliente.direccion || '';
                        telefonoInput.value = cliente.telefono || '';
                        emailInput.value = cliente.correo || '';
                        hideClientMessage();
                    } else {
                        tipoIdentificacionInput.value = '';
                        razonSocialInput.value = '';
                        direccionInput.value = '';
                        telefonoInput.value = '';
                        emailInput.value = '';
                        showClientMessage(`Cliente no encontrado con identificación <strong>${identificacionInput.value.trim()}</strong>. Debe registrar al cliente primero.`);
                    }
                }
                function determinarTipoIdentificacion(identificacion) {
                    identificacion = String(identificacion).trim();
                    if (/^\d{13}$/.test(identificacion)) return 'RUC'; // cualquier 13 dígitos es RUC
                    if (/^\d{10}$/.test(identificacion)) return 'Cedula';
                    if (identificacion === '9999999999999' || identificacion === '9999999999') return 'ConsumidorFinal';
                    if (identificacion.length >= 6 && identificacion.length <= 20) return 'Pasaporte';
                    return 'Otro';
                }
                async function searchClient() {
                    const ruc_cedula = identificacionInput.value.trim();
                    clearAdquirenteFields();
                    if (ruc_cedula.length === 0) {
                        showClientMessage('Por favor, ingrese una identificación.', 'alert-warning');
                        return;
                    }
                    let tipoIdentificacionDeterminado = determinarTipoIdentificacion(ruc_cedula);
                    tipoIdentificacionInput.value = tipoIdentificacionDeterminado;
                    if (tipoIdentificacionDeterminado === 'ConsumidorFinal') {
                        razonSocialInput.value = 'CONSUMIDOR FINAL';
                        direccionInput.value = 'VENTA AL POR MENOR';
                        telefonoInput.value = '';
                        emailInput.value = 'correo@ejemplo.com';
                        hideClientMessage();
                        return;
                    }
                    const tiposBuscablesEnBD = ['RUC', 'Cedula', 'Pasaporte'];
                    if (!tiposBuscablesEnBD.includes(tipoIdentificacionDeterminado)) {
                        showClientMessage(
                            `Cliente no encontrado con identificación <strong>${ruc_cedula}</strong> (tipo: ${tipoIdentificacionDeterminado}). Debe registrar al cliente primero.`,
                            'alert-info'
                        );
                        return;
                    }
                    try {
                        const response = await fetch(`cx/api.php?action=getClienteByIdentificacion&ruc_cedula=${ruc_cedula}&tipoIdentificacion=${tipoIdentificacionDeterminado}`);
                        if (!response.ok) {
                            showClientMessage(
                                `Cliente no encontrado con identificación <strong>${ruc_cedula}</strong>. Debe registrar al cliente primero.`,
                                'alert-danger'
                            );
                            return;
                        }
                        const data = await response.json();
                        if (data.error || !data.cliente) {
                            populateAdquirenteFields(null);
                        } else {
                            populateAdquirenteFields(data.cliente);
                        }
                    } catch {
                        showClientMessage(
                            `Cliente no encontrado con identificación <strong>${ruc_cedula}</strong>. Debe registrar al cliente primero.`,
                            'alert-danger'
                        );
                    }
                }

                // --- Eventos ---
                selectEstablecimiento.addEventListener('change', function () {
                    const selectedEstablecimientoId = this.value;
                    if (selectedEstablecimientoId) cargarPuntosEmision(selectedEstablecimientoId);
                    else {
                        selectPuntoEmision.innerHTML = '<option value="">Seleccione un establecimiento</option>';
                        selectPuntoEmision.disabled = true;
                    }
                });
                cargarEstablecimientos();

                document.getElementById('cuerpo-tabla-productos').addEventListener('input', function (e) {
                    if (e.target.classList.contains('cantidad') || e.target.classList.contains('descuento')) {
                        const row = e.target.closest('tr');
                        const cantidad = parseFloat(row.querySelector('.cantidad').value) || 0;
                        const precio = parseFloat(row.querySelector('.precio-unitario').value) || 0;
                        const descuento = parseFloat(row.querySelector('.descuento')?.value) || 0;
                        const subtotal = (cantidad * precio) - descuento;
                        row.querySelector('.subtotal-fila').textContent = subtotal.toFixed(2);
                        calcularTotales();
                    }
                });
                document.getElementById('cuerpo-tabla-productos').addEventListener('click', function (e) {
                    if (e.target.classList.contains('btn-eliminar')) {
                        e.target.closest('tr').remove();
                        calcularTotales();
                    }
                });
                tablaModalProductos.addEventListener('click', (e) => {
                    if (!e.target.classList.contains('btn-accion')) return;
                    const btn = e.target;
                    const id = btn.getAttribute('data-id');
                    const codigo = btn.getAttribute('data-codigo');
                    const descripcion = btn.getAttribute('data-descripcion');
                    const precio = parseFloat(btn.getAttribute('data-precio'));
                    const iva = parseFloat(btn.getAttribute('data-iva'));
                    if (document.querySelector(`#cuerpo-tabla-productos tr[data-id="${id}"]`)) {
                        alert("Este producto ya ha sido agregado.");
                        return;
                    }
                    const tablaFactura = document.getElementById('cuerpo-tabla-productos');
                    const row = document.createElement('tr');
                    row.setAttribute('data-id', id);
                    row.setAttribute('data-iva-rate', iva);
                    row.innerHTML = `
                        <td>${codigo}</td>
                        <td>${descripcion}</td>
                        <td><input type="number" class="form-control form-control-sm cantidad" value="1" min="1" step="1"></td>
                        <td><input type="text" class="form-control form-control-sm precio-unitario" value="${precio.toFixed(2)}" readonly></td>
                        <td><input type="number" class="form-control form-control-sm descuento" value="0" min="0" step="0.01"></td>
                        <td class="subtotal-fila">${precio.toFixed(2)}</td>
                        <td><button class="btn btn-danger btn-sm btn-eliminar">🗑</button></td>
                    `;
                    tablaFactura.appendChild(row);
                    modalBuscarProducto.hide();
                    calcularTotales();
                });
                document.getElementById('checkPropina').addEventListener('change', calcularTotales);
                document.getElementById('btnGuardarFormaPago').addEventListener('click', agregarFormaPago);
                document.getElementById('btnGuardarCampoAdicional').addEventListener('click', agregarCampoAdicional);

                // Modal productos
                const modalBuscarProductoElement = document.getElementById('modalBuscarProducto');
                const modalBuscarProducto = new bootstrap.Modal(modalBuscarProductoElement);
                modalBuscarProductoElement.addEventListener('show.bs.modal', () => {
                    inputBuscar.value = '';
                    cargarProductos('');
                });
                inputBuscar.addEventListener('input', () => {
                    cargarProductos(inputBuscar.value.trim());
                });

                // Limpieza de backdrop de modales
                document.querySelectorAll('.modal').forEach(modalEl => {
                    modalEl.addEventListener('hidden.bs.modal', () => {
                        setTimeout(() => {
                            if (document.querySelectorAll('.modal.show').length === 0) {
                                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                                document.body.classList.remove('modal-open');
                            }
                        }, 200);
                    });
                });

                // Cliente
                if (btnBuscarCliente) btnBuscarCliente.addEventListener('click', searchClient);
                identificacionInput.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        searchClient();
                    }
                });
                identificacionInput.addEventListener('blur', function() {
                    if (this.value.trim().length > 0) searchClient();
                    else clearAdquirenteFields();
                });

                // Guardar factura sin firmar
                document.getElementById('btnSoloGuardar').addEventListener('click', function () {
                    const datos = obtenerDatosFactura();
                    datos.estado = 'borrador';
                    fetch('guardar_factura.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(datos)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) alert('Factura guardada sin firmar');
                        else alert('Error: ' + data.message);
                    });
                });

                // Inicialización
                clearAdquirenteFields();
                calcularTotales();
            });

            document.getElementById('formFactura').addEventListener('submit', function(e) {
                e.preventDefault();
                // Mostrar modal de firma
                const modalFirma = new bootstrap.Modal(document.getElementById('modalFirma'));
                modalFirma.show();
            });

            document.getElementById('formFirma').addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Submit modal firma');
                const claveFirma = document.getElementById('claveFirma').value;
                const datosFactura = obtenerDatosFactura(); // Debes tener esta función JS que arma el JSON de la factura
                datosFactura.claveFirma = claveFirma;

                // Opcional: muestra cargando
                const mensaje = document.getElementById('firmaMensaje');
                mensaje.className = 'alert alert-info';
                mensaje.textContent = 'Firmando y enviando al SRI...';
                mensaje.classList.remove('d-none');

                fetch('firmar_enviar_sri.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(datosFactura)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mensaje.className = 'alert alert-success';
                        mensaje.textContent = 'Factura firmada y enviada correctamente. Autorización: ' + (data.autorizacion || '');
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        mensaje.className = 'alert alert-danger';
                        mensaje.textContent = 'Error: ' + (data.message || 'No se pudo firmar/enviar.');
                    }
                })
                .catch(() => {
                    mensaje.className = 'alert alert-danger';
                    mensaje.textContent = 'Error de comunicación con el servidor.';
                });
            });

            document.getElementById('modalCampoAdicional').addEventListener('show.bs.modal', function () {
                const valorTotal = document.getElementById('valorTotal').textContent.trim();
                const inputValor = document.getElementById('campoAdicionalValor');
            });

            const modalFormaPagoElement = document.getElementById('modalFormaPago');
            const modalFormaPago = new bootstrap.Modal(modalFormaPagoElement);

            modalFormaPagoElement.addEventListener('show.bs.modal', () => {
                cargarFormasPago(); // ya lo tienes, bien

                // Aquí asigna el valor inicial para el input, por ejemplo el total de la factura
                const valorTotal = parseFloat(document.getElementById('valorTotal').textContent) || 0;
                document.getElementById('formaPagoValor').value = valorTotal.toFixed(2);
            });

            // Marca el input como modificado si el usuario escribe algo
            document.getElementById('campoAdicionalValor').addEventListener('input', function () {
                this.dataset.modificado = 'true';
            });

        </script>
    </div>
<?php include_once 'footer.php'; ?>