<?php
include_once 'header.php';
include_once 'auth_session.php';
include_once 'cx/f_cx.php';

$hoy = date('Y-m-d');
$sql = "SELECT f.id_factura, f.total FROM facturas f WHERE DATE(f.fecha_emision) = :hoy AND f.estado_registro = :estado";
$params = [':hoy' => $hoy, ':estado' => 'activo'];
$facturas = ejecutarConsultaSegura($sql, $params);

$total_facturas = count($facturas);
$total_ventas = 0;
$productos_vendidos = 0;

if ($total_facturas > 0) {
    foreach ($facturas as $factura) {
        $total_ventas += floatval($factura['total']);
        // Sumar productos vendidos por factura
        $sqlDetalle = "SELECT SUM(cantidad) as suma FROM detalle_factura WHERE id_factura = :id_factura";
        $detalle = ejecutarConsultaSegura($sqlDetalle, [':id_factura' => $factura['id_factura']], true);
        $productos_vendidos += intval($detalle['suma']);
    }
}
?>
<div class="container mt-4">
    <h2 class="mb-4">Resumen Diario de Ventas</h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm text-center" style="background: #ffe5ec;">
                <div class="card-body">
                    <h5 class="card-title text-dark">Facturas Emitidas</h5>
                    <p class="display-6 text-dark"><?php echo $total_facturas; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center" style="background: #e0f7fa;">
                <div class="card-body">
                    <h5 class="card-title text-dark">Total Vendido</h5>
                    <p class="display-6 text-dark">$<?php echo number_format($total_ventas, 2); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center" style="background: #e8f5e9;">
                <div class="card-body">
                    <h5 class="card-title text-dark">Productos Vendidos</h5>
                    <p class="display-6 text-dark"><?php echo $productos_vendidos; ?></p>
                </div>
            </div>
        </div>
    </div>
    <canvas id="ventasPorHora" height="100"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('ajax/api_ventas_hora.php')
  .then(res => res.json())
  .then(datos => {
    const ctx = document.getElementById('ventasPorHora').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: datos.horas,
        datasets: [{
          label: 'Ventas por Hora',
          data: datos.valores,
          backgroundColor: [
            '#ffd6e0', '#b5ead7', '#c7ceea', '#f3eac2', '#f7cac9', '#b5ead7', '#c7ceea',
            '#f3eac2', '#f7cac9', '#b5ead7', '#c7ceea', '#f3eac2', '#f7cac9', '#b5ead7',
            '#c7ceea', '#f3eac2', '#f7cac9', '#b5ead7', '#c7ceea', '#f3eac2', '#f7cac9', '#b5ead7', '#c7ceea', '#f3eac2'
          ],
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        animation: {
          duration: 1200,
          easing: 'easeOutBounce'
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  });
</script>
<?php include_once 'footer.php'; ?>