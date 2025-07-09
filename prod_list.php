<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  include_once 'header.php';
  include_once "cx/f_cx.php";
  $q = "SELECT p.id_producto,p.codigo,p.nombre,p.descripcion,c.nombre_categoria,p.precio_unitario,p.stock,p.estado
  FROM facturacion.productos AS p INNER JOIN facturacion.categorias AS c ON c.id_categoria=p.id_categoria WHERE p.estado='activo' AND c.estado='activo'";
  $r = ejecutarConsultaSegura($q);
?>
<div class="card shadow-lg animate__animated animate__fadeIn">
  <div class="card-body">
    <h4 class="card-title mb-4 text-center">Listado de Productos</h4>
    <div class="mb-3 text-end">
      <button id="btn-nuevo-producto" class="btn btn-primary btn-sm mb-3">Nuevo Producto</button>    
    </div>
    <div class="table-responsive">
      <table id="tabla-productos" class="table table-striped table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Acciones</th> 
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($r)): ?>
            <?php foreach ($r as $p): ?>
              <tr>
                <td class="text-center"><?= $p['id_producto'] ?></td>
                <td><?= htmlspecialchars($p['codigo']) ?></td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= htmlspecialchars($p['descripcion']) ?></td>
                <td><?= htmlspecialchars($p['nombre_categoria']) ?></td>
                <td class="text-end">$<?= number_format($p['precio_unitario'], 2) ?></td>
                <td class="text-center"><?= $p['stock'] ?></td>
                <td class="text-center"><?= $p['estado'] ?></td>
                <td class="text-center">
                  <button class="btn btn-warning btn-sm modificar-producto"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                  </svg></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">No se encontraron productos</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
$(function () {
  let table = $('#tabla-productos').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    order: [[ 0, "desc" ]]
  });

  // Nuevo producto
  let editando = false;

  $('#btn-nuevo-producto').on('click', function () {
    if (editando) return;
    editando = true;
    const svgSave = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
        <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
      </svg>`;
    const svgCancel = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
      </svg>`;
    let filaNueva = $(`
      <tr class="nuevo-producto">
        <td></td>
        <td><input type="text" class="form-control form-control-sm" name="codigo" required></td>
        <td><input type="text" class="form-control form-control-sm" name="nombre" required></td>
        <td><input type="text" class="form-control form-control-sm" name="descripcion"></td>
        <td>
          <select class="form-select form-select-sm" name="id_categoria">
            <!-- Opciones se cargan dinámicamente -->
          </select>
        </td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="precio_unitario" required></td>
        <td><input type="number" class="form-control form-control-sm" name="stock" required></td>
        <td>
          <select class="form-select form-select-sm" name="estado">
            <option value="activo">activo</option>
            <option value="inactivo">inactivo</option>
          </select>
        </td>
        <td>
          <button class="btn btn-success btn-sm guardar-producto">${svgSave}</button>
          <button class="btn btn-secondary btn-sm cancelar-producto">${svgCancel}</button>
        </td>
      </tr>
    `);

    $('#tabla-productos tbody').prepend(filaNueva);

    // Cargar categorías dinámicamente
    $.getJSON('ajax/listar_categorias.php', function (categorias) {
      let select = filaNueva.find('select[name="id_categoria"]');
      categorias.forEach(c => {
        select.append(`<option value="${c.id_categoria}">${c.nombre_categoria}</option>`);
      });
    });
  });

  // Guardar nuevo producto
  $('#tabla-productos tbody').on('click', '.guardar-producto', function () {
    let $fila = $(this).closest('tr');

    let datos = {
      codigo: $fila.find('input[name="codigo"]').val().trim(),
      nombre: $fila.find('input[name="nombre"]').val().trim(),
      descripcion: $fila.find('input[name="descripcion"]').val().trim(),
      id_categoria: $fila.find('select[name="id_categoria"]').val(),
      precio_unitario: $fila.find('input[name="precio_unitario"]').val(),
      stock: $fila.find('input[name="stock"]').val(),
      estado: $fila.find('select[name="estado"]').val()
    };

    if (!datos.codigo || !datos.nombre || !datos.precio_unitario || !datos.stock) {
      alert('Todos los campos obligatorios deben completarse');
      return;
    }

    $.post('ajax/insertar_producto.php', datos, function (resp) {
      if (resp.success) {
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
        let nuevaFila = table.row.add([
          resp.id_producto,
          $('<div>').text(datos.codigo).html(),
          $('<div>').text(datos.nombre).html(),
          $('<div>').text(datos.descripcion).html(),
          $('<div>').text(resp.nombre_categoria).html(),
          `$${parseFloat(datos.precio_unitario).toFixed(2)}`,
          datos.stock,
          datos.estado,
          `<button class="btn btn-warning btn-sm modificar-producto">${svgPencil}</button>`
        ]).draw(false).node();

        $(nuevaFila).find('td').addClass('align-middle');
        $fila.remove();
        editando = false;
      } else {
        alert(resp.message || 'Error al insertar el producto');
      }
    }, 'json').fail(function () {
      alert('Error de comunicación con el servidor');
    });
  });

  // Cancelar nuevo producto
  $('#tabla-productos tbody').on('click', '.cancelar-producto', function () {
    $(this).closest('tr').remove();
    editando = false;
  });

  // Modificar producto existente
  $('#tabla-productos tbody').on('click', '.modificar-producto', function () {
    if (editando) return;
    editando = true;

    let $fila = $(this).closest('tr');
    let tds = $fila.find('td');

    let data = {
      id: tds.eq(0).text().trim(),
      codigo: tds.eq(1).text().trim(),
      nombre: tds.eq(2).text().trim(),
      descripcion: tds.eq(3).text().trim(),
      nombre_categoria: tds.eq(4).text().trim(),
      precio_unitario: tds.eq(5).text().replace('$', '').trim(),
      stock: tds.eq(6).text().trim(),
      estado: tds.eq(7).text().trim()
    };

    $fila.data('original', data);
    const svgSave = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
        <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
      </svg>`;
    const svgCancel = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
      </svg>`;
    // Reemplazar celdas con inputs
    tds.eq(1).html(`<input type="text" class="form-control form-control-sm" name="codigo" value="${data.codigo}" required>`);
    tds.eq(2).html(`<input type="text" class="form-control form-control-sm" name="nombre" value="${data.nombre}" required>`);
    tds.eq(3).html(`<input type="text" class="form-control form-control-sm" name="descripcion" value="${data.descripcion}">`);
    tds.eq(5).html(`<input type="number" class="form-control form-control-sm text-end" name="precio_unitario" value="${data.precio_unitario}" step="0.01">`);
    tds.eq(6).html(`<input type="number" class="form-control form-control-sm text-center" name="stock" value="${data.stock}">`);
    tds.eq(7).html(`
      <select class="form-select form-select-sm" name="estado">
        <option value="activo"${data.estado === 'activo' ? ' selected' : ''}>activo</option>
        <option value="inactivo"${data.estado === 'inactivo' ? ' selected' : ''}>inactivo</option>
      </select>
    `);
    tds.eq(8).html(`
      <button class="btn btn-success btn-sm guardar-edicion-producto">${svgSave}</button>
      <button class="btn btn-secondary btn-sm cancelar-edicion-producto">${svgCancel}</button>
    `);

    // Cargar categorías dinámicamente y seleccionar la correspondiente
    $.getJSON('ajax/listar_categorias.php', function (categorias) {
      let selectHTML = `<select class="form-select form-select-sm" name="id_categoria">`;
      categorias.forEach(c => {
        let selected = (c.nombre_categoria === data.nombre_categoria) ? 'selected' : '';
        selectHTML += `<option value="${c.id_categoria}" ${selected}>${c.nombre_categoria}</option>`;
      });
      selectHTML += `</select>`;
      tds.eq(4).html(selectHTML);
    });
  });

  // Guardar edición
  $('#tabla-productos tbody').on('click', '.guardar-edicion-producto', function () {
    let $fila = $(this).closest('tr');
    let id = $fila.find('td').eq(0).text().trim();
    let data = {
      id_producto: id,
      codigo: $fila.find('[name="codigo"]').val().trim(),
      nombre: $fila.find('[name="nombre"]').val().trim(),
      descripcion: $fila.find('[name="descripcion"]').val().trim(),
      id_categoria: $fila.find('[name="id_categoria"]').val(),
      precio_unitario: $fila.find('[name="precio_unitario"]').val(),
      stock: $fila.find('[name="stock"]').val(),
      estado: $fila.find('[name="estado"]').val()
    };

    $.post('ajax/actualizar_producto.php', data, function (resp) {
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
        table.row($fila).data([
          data.id_producto,
          data.codigo,
          data.nombre,
          data.descripcion,
          resp.nombre_categoria,
          `$${parseFloat(data.precio_unitario).toFixed(2)}`,
          data.stock,
          data.estado,
          `<button class="btn btn-warning btn-sm modificar-producto">${svgPencil}</button>`
        ]).draw(false);
        editando = false;
      } else {
        alert(resp.message || 'Error al actualizar.');
      }
    }, 'json').fail(function () {
      alert('Error de red.');
    });
  });

  // Cancelar edición
  $('#tabla-productos tbody').on('click', '.cancelar-edicion-producto', function () {
    let $fila = $(this).closest('tr');
    let original = $fila.data('original');
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
    if (original) {
      table.row($fila).data([
        original.id,
        original.codigo,
        original.nombre,
        original.descripcion,
        original.nombre_categoria,
        `$${parseFloat(original.precio_unitario).toFixed(2)}`,
        original.stock,
        original.estado,
        `<button class="btn btn-warning btn-sm modificar-producto">${svgPencil}</button>`
      ]).draw(false);
      editando = false;
    }
  });

});
</script>
<?php include_once 'footer.php'; ?>