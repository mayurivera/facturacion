<?php
include_once 'header.php';
include_once "cx/f_cx.php";
require_once 'vendor/autoload.php';

$q = "SELECT 
        c.id_cliente, 
        c.razon_social, 
        c.ruc_cedula, 
        c.tipo_identificacion, 
        c.direccion, 
        c.telefono, 
        c.correo, 
        c.estado 
      FROM facturacion.clientes AS c 
      WHERE c.estado = 'activo'
      ORDER BY c.id_cliente DESC";

$r = ejecutarConsultaSegura($q);
$mostrarFilaNuevo = empty($r) ? '' : 'display: none;';
?>

<div class="card shadow-lg animate__animated animate__fadeIn">
  <div class="card-body">
    <h4 class="card-title mb-4 text-center">Listado de Clientes</h4>
    <div class="mb-3 text-end">
      <button id="btn-nuevo-cliente" class="btn btn-primary btn-sm mb-3">Nuevo Cliente</button>
    </div>
    <div class="table-responsive">
      <table id="tabla-clientes" class="table table-striped table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Razón Social</th>
            <th>Identificación</th>
            <th>Tipo ID</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- Fila para nuevo cliente -->
          <tr id="fila-nuevo-cliente" class="table-success" style="<?= $mostrarFilaNuevo ?>">
            <td class="text-center">#</td>
            <td><input type="text" class="form-control form-control-sm" id="nuevo_razon_social" placeholder="Razón Social"></td>
            <td><input type="text" class="form-control form-control-sm" id="nuevo_ruc_cedula" placeholder="RUC/Cédula"></td>
            <td>
              <select class="form-select form-select-sm" id="nuevo_tipo_identificacion">
                <option value="">Tipo</option>
                <option value="RUC">RUC</option>
                <option value="Cedula">Cédula</option>
              </select>
            </td>
            <td><input type="text" class="form-control form-control-sm" id="nuevo_direccion" placeholder="Dirección"></td>
            <td><input type="text" class="form-control form-control-sm" id="nuevo_telefono" placeholder="Teléfono"></td>
            <td><input type="email" class="form-control form-control-sm" id="nuevo_correo" placeholder="Correo"></td>
            <td>
              <select class="form-select form-select-sm" id="nuevo_estado">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </td>
            <td class="text-center">
              <button class="btn btn-success btn-sm btn-agregar-cliente"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
                <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
                <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
              </svg></button>
            </td>
          </tr>

          <!-- Clientes existentes -->
          <?php foreach ($r as $c): ?>
            <tr>
              <td class="text-center"><?= $c['id_cliente'] ?></td>
              <td><?= htmlspecialchars($c['razon_social']) ?></td>
              <td><?= htmlspecialchars($c['ruc_cedula']) ?></td>
              <td class="text-center"><?= htmlspecialchars($c['tipo_identificacion']) ?></td>
              <td><?= htmlspecialchars($c['direccion']) ?></td>
              <td><?= htmlspecialchars($c['telefono']) ?></td>
              <td><?= htmlspecialchars($c['correo']) ?></td>
              <td class="text-center"><?= htmlspecialchars($c['estado']) ?></td>
              <td class="text-center">
                <button class="btn btn-warning btn-sm modificar-cliente"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(function () {
    const table = $('#tabla-clientes').DataTable({
      language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
      responsive: true,
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50],
      order: [[0, "desc"]],
      columnDefs: [
        { targets: 0, type: 'num' } // fuerza que la columna ID sea numérica
      ]
    });

    // Mostrar/ocultar fila nuevo cliente
    $('#btn-nuevo-cliente').on('click', function () {
      const fila = $('#fila-nuevo-cliente');
      if (fila.is(':visible')) {
        fila.hide();
        fila.find('input, select').val('');
      } else {
        fila.show();
        fila.find('input, select').val('');
      }
    });

    // Validaciones básicas
    function validarEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }

    function validarTelefono(tel) {
      const re = /^[0-9+\-\s]{6,20}$/;
      return re.test(tel);
    }

    // Evento delegado para agregar cliente
    $(document).on('click', '.btn-agregar-cliente', function () {
      console.log('Botón agregar cliente pulsado');

      let data = {
        razon_social: $('#nuevo_razon_social').val().trim(),
        ruc_cedula: $('#nuevo_ruc_cedula').val().trim(),
        tipo_identificacion: $('#nuevo_tipo_identificacion').val(),
        direccion: $('#nuevo_direccion').val().trim(),
        telefono: $('#nuevo_telefono').val().trim(),
        correo: $('#nuevo_correo').val().trim(),
        estado: $('#nuevo_estado').val()
      };

      console.log('Datos para guardar:', data);

      if (!data.razon_social || !data.ruc_cedula || !data.tipo_identificacion || !data.estado) {
        mostrarToast('Por favor complete todos los campos obligatorios.', 'danger');
        return;
      }

      if (data.correo && !validarEmail(data.correo)) {
        mostrarToast('Ingrese un correo válido.', 'danger');
        return;
      }

      if (data.telefono && !validarTelefono(data.telefono)) {
        mostrarToast('Ingrese un teléfono válido.', 'danger');
        return;
      }

      $.post('ajax/validar_identificacion.php', {
        tipo_identificacion: data.tipo_identificacion,
        ruc_cedula: data.ruc_cedula
      }, function (resp) {
        console.log('Respuesta validación:', resp);
        if (!resp.success) {
          mostrarToast(resp.message || 'Identificación inválida.', 'danger');
          $('#nuevo_ruc_cedula').addClass('is-invalid').removeClass('is-valid');
          return;
        }

        $('#nuevo_ruc_cedula').removeClass('is-invalid').addClass('is-valid');

        // Ahora guardar el cliente
        $.post('ajax/guardar_cliente.php', data, function (resp2) {
          if (resp2.success) {
            $('#fila-nuevo-cliente').hide();
            $('#fila-nuevo-cliente input, #fila-nuevo-cliente select').val('');

            const svgPencil = `
              <svg xmlns="http://www.w3.org/2000/svg"
                  width="16" height="16" fill="currentColor"
                  class="bi bi-pencil-square"
                  viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 
                        3.69l-2-2L13.502.646a.5.5 0 0 1 .707 
                        0l1.293 1.293zm-1.75 2.456-2-2L4.939 
                        9.21a.5.5 0 0 0-.121.196l-.805 
                        2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 
                        0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd"
                      d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 
                        1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 
                        0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 
                        1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 
                        0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 
                        2.5z"/>
              </svg>`;

            table.row.add([
              resp2.cliente.id_cliente,
              resp2.cliente.razon_social,
              resp2.cliente.ruc_cedula,
              resp2.cliente.tipo_identificacion,
              resp2.cliente.direccion,
              resp2.cliente.telefono,
              resp2.cliente.correo,
              resp2.cliente.estado,
              `<button class="btn btn-warning btn-sm modificar-cliente" aria-label="Modificar">${svgPencil}</button>`
            ]).order([0, 'desc']).draw(false);

            mostrarToast('Cliente agregado correctamente.');
          } else {
            mostrarToast(resp2.message || 'Error al agregar cliente.', 'danger');
          }
        }, 'json').fail(function () {
          mostrarToast('Error de comunicación con el servidor.', 'danger');
        });

      }, 'json').fail(function () {
        mostrarToast('Error de validación en el servidor.', 'danger');
      });
    });

    // Delegar evento modificar cliente (inline edit)
    $('#tabla-clientes tbody').on('click', '.modificar-cliente', function () {
      const fila = $(this).closest('tr');
      const data = table.row(fila).data();
      const svgSave = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
        <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
      </svg>`;
      // Cambiar a inputs/select para editar
      fila.find('td:eq(1)').html(`<input class="form-control form-control-sm" value="${data[1]}">`);
      fila.find('td:eq(2)').html(`<input class="form-control form-control-sm" value="${data[2]}">`);
      fila.find('td:eq(3)').html(`
        <select class="form-select form-select-sm">
          <option value="RUC" ${data[3] === 'RUC' ? 'selected' : ''}>RUC</option>
          <option value="Cedula" ${data[3] === 'Cedula' ? 'selected' : ''}>Cédula</option>
          <option value="Pasaporte" ${data[3] === 'Pasaporte' ? 'selected' : ''}>Pasaporte</option>
        </select>`);
      fila.find('td:eq(4)').html(`<input class="form-control form-control-sm" value="${data[4]}">`);
      fila.find('td:eq(5)').html(`<input class="form-control form-control-sm" value="${data[5]}">`);
      fila.find('td:eq(6)').html(`<input class="form-control form-control-sm" value="${data[6]}">`);
      fila.find('td:eq(7)').html(`
        <select class="form-select form-select-sm">
          <option value="activo" ${data[7] === 'activo' ? 'selected' : ''}>Activo</option>
          <option value="inactivo" ${data[7] === 'inactivo' ? 'selected' : ''}>Inactivo</option>
        </select>`);
      fila.find('td:eq(8)').html(`<button class="btn btn-success btn-sm guardar-cliente">${svgSave}</button>`);
    });

    // Delegar evento guardar cliente editado
    $('#tabla-clientes tbody').on('click', '.guardar-cliente', function () {
      const fila = $(this).closest('tr');
      const id = fila.find('td:eq(0)').text();

      const data = {
        id_cliente: id,
        razon_social: fila.find('td:eq(1) input').val().trim(),
        ruc_cedula: fila.find('td:eq(2) input').val().trim(),
        tipo_identificacion: fila.find('td:eq(3) select').val(),
        direccion: fila.find('td:eq(4) input').val().trim(),
        telefono: fila.find('td:eq(5) input').val().trim(),
        correo: fila.find('td:eq(6) input').val().trim(),
        estado: fila.find('td:eq(7) select').val()
      };

      if (!data.razon_social || !data.ruc_cedula || !data.tipo_identificacion || !data.estado) {
        alert('Por favor complete todos los campos obligatorios.');
        return;
      }

      if (data.correo && !validarEmail(data.correo)) {
        alert('Ingrese un correo válido.');
        return;
      }

      if (data.telefono && !validarTelefono(data.telefono)) {
        alert('Ingrese un teléfono válido.');
        return;
      }

      // Validar identificación con llamada AJAX igual que para agregar nuevo cliente
      $.post('ajax/validar_identificacion.php', {
        tipo_identificacion: data.tipo_identificacion,
        ruc_cedula: data.ruc_cedula
      }, function (resp) {
        if (!resp.success) {
          alert(resp.message || 'Identificación inválida.');
          fila.find('td:eq(2) input').addClass('is-invalid');
          return;
        } else {
          fila.find('td:eq(2) input').removeClass('is-invalid');

          // Si pasa validación, guardamos
          $.post('ajax/guardar_cliente.php', data, function (resp) {
            const svgPencil = `
              <svg xmlns="http://www.w3.org/2000/svg"
                  width="16" height="16" fill="currentColor"
                  class="bi bi-pencil-square"
                  viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 
                        3.69l-2-2L13.502.646a.5.5 0 0 1 .707 
                        0l1.293 1.293zm-1.75 2.456-2-2L4.939 
                        9.21a.5.5 0 0 0-.121.196l-.805 
                        2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 
                        0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd"
                      d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 
                        1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 
                        0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 
                        1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 
                        0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 
                        2.5z"/>
              </svg>`;

            if (resp.success) {
              table.row(fila).data([
                data.id_cliente,
                data.razon_social,
                data.ruc_cedula,
                data.tipo_identificacion,
                data.direccion,
                data.telefono,
                data.correo,
                data.estado,
                `<button class="btn btn-warning btn-sm modificar-cliente" aria-label="Modificar">
                  ${svgPencil}
                </button>`
              ]).draw(false);

              mostrarToast('Cliente actualizado correctamente.');
            } else {
              alert(resp.message || 'Error al guardar los cambios');
            }
          }, 'json').fail(function () {
            alert('Error de comunicación con el servidor.');
          });
        }
      }, 'json').fail(function () {
        alert('Error de validación en el servidor.');
      });
    });

    // Función para mostrar mensajes tipo toast (ajusta o usa tu propia implementación)
    function mostrarToast(mensaje, tipo = 'success') {
      // Aquí podrías usar Bootstrap Toasts o cualquier notificación
      alert(mensaje); // Simple para prueba
    }
  });
</script>

<?php include_once 'footer.php'; ?>
