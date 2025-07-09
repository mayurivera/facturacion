<div class="list-group list-group-flush">

  <!-- DASHBOARD -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuInicio" role="button">
    <i class="bi bi-speedometer2"></i> Dashboard
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuInicio">
    <a href="resumen_diario.php" class="list-group-item list-group-item-action">Resumen Diario</a>
  </div>

  <!-- FACTURACIÓN -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuFacturacion" role="button">
    <i class="bi bi-receipt-cutoff"></i> Facturación Electrónica
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuFacturacion">
    <a href="emitirFactura.php" class="list-group-item list-group-item-action">1. Emitir Factura Electrónica</a>
    <a href="firmarXML.php" class="list-group-item list-group-item-action">2. Firmar XML (manual)</a>
    <a href="enviarComprobante.php" class="list-group-item list-group-item-action">3. Enviar al SRI (manual)</a>
    <a href="autorizarComprobante.php" class="list-group-item list-group-item-action">4. Consultar Autorización</a>
    <a href="facturasEmitidas.php" class="list-group-item list-group-item-action">5. Facturas Emitidas</a>
    <a href="anuladasContingencia.php" class="list-group-item list-group-item-action">6. Anuladas / Contingencia</a>
    <a href="reimprimir_factura.php" class="list-group-item list-group-item-action">7. Reimprimir Factura</a>
    <a href="nota_credito.php" class="list-group-item list-group-item-action">8. Notas de Crédito</a>
  </div>

  <!-- VENTAS -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuVentas" role="button">
    <i class="bi bi-cart-check"></i> Ventas
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuVentas">
    <a href="ventas_directas.php" class="list-group-item list-group-item-action">1. Venta con Factura</a>
    <a href="devoluciones.php" class="list-group-item list-group-item-action">2. Devoluciones / NC</a>
    <a href="detalle_ventas.php" class="list-group-item list-group-item-action">3. Detalle de Ventas</a>
  </div>

  <!-- CLIENTES -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuClientes" role="button">
    <i class="bi bi-person-lines-fill"></i> Clientes
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuClientes">
    <a href="cli_list.php" class="list-group-item list-group-item-action">Listado</a>
  </div>

  <!-- PRODUCTOS / SERVICIOS -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuProductos" role="button">
    <i class="bi bi-box-seam"></i> Productos / Servicios
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuProductos">
    <a href="prod_list.php" class="list-group-item list-group-item-action">Productos</a>
    <a href="prod_categ.php" class="list-group-item list-group-item-action">Categorías</a>
  </div>

  <!-- REPORTES -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuReportes" role="button">
    <i class="bi bi-bar-chart-line"></i> Reportes
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuReportes">
    <a href="#" class="list-group-item list-group-item-action">Ventas por Rango</a>
    <a href="#" class="list-group-item list-group-item-action">Ventas por Cliente</a>
    <a href="#" class="list-group-item list-group-item-action">Kardex</a>
  </div>

  <!-- CONFIGURACIÓN -->
  <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#submenuAjustes" role="button">
    <i class="bi bi-gear"></i> Configuración
    <i class="bi bi-chevron-down float-end"></i>
  </a>
  <div class="collapse ps-3" id="submenuAjustes">
    <a href="mi_perfil.php" class="list-group-item list-group-item-action">Mi Perfil</a>
    <a href="usuarios_roles.php" class="list-group-item list-group-item-action">Roles de Usuario</a>
    <a href="parametros_sri.php" class="list-group-item list-group-item-action">Parámetros SRI</a>
    <a href="certificado_digital.php" class="list-group-item list-group-item-action">Certificado Digital</a>
  </div>

  <!-- CERRAR SESIÓN -->
  <a href="logout.php" class="list-group-item list-group-item-action text-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
  </a>
</div>
