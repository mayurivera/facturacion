<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  include_once 'header.php';
  include_once "cx/f_cx.php";
  $q = "SELECT c.id_categoria,c.nombre_categoria,c.descripcion,c.estado FROM facturacion.categorias AS c";
  $r = ejecutarConsultaSegura($q);
?>
<div class="card shadow-lg animate__animated animate__fadeIn"></div>
  <div class="card-body">
    <h4 class="card-title mb-4 text-center">Listado de Categorías</h4>
    <div class="mb-3 text-end">
      <button id="btn-nueva-categoria" class="btn btn-primary">Nueva Categoría</button>
    </div>
    <div class="table-responsive">
      <table id="tabla-productos" class="table table-striped table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($r)): ?>
            <?php foreach ($r as $p): ?>
              <tr>
                <td class="text-center"><?= $p['id_categoria'] ?></td>
                <td><?= htmlspecialchars($p['nombre_categoria']) ?></td>
                <td><?= htmlspecialchars($p['descripcion']) ?></td>
                <td><?= htmlspecialchars($p['estado']) ?></td>
                <td>
                  <button class="btn btn-warning btn-sm modificar-categoria"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                  </svg></button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No se encontraron categorías</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
$(function () {
  let editando = false;
  let table = $('#tabla-productos').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    order: [[ 0, "desc" ]]
  });

  // Nueva categoría
  $('#btn-nueva-categoria').on('click', function () {
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
    let filaNueva = $(`<tr class="nueva-categoria">
      <td></td>
      <td><input type="text" class="form-control form-control-sm" name="nombre_categoria" required></td>
      <td><input type="text" class="form-control form-control-sm" name="descripcion"></td>
      <td>
        <select class="form-select form-select-sm" name="estado">
          <option value="activo">activo</option>
          <option value="inactivo">inactivo</option>
        </select>
      </td>
      <td>
        <button class="btn btn-success btn-sm guardar-categoria">${svgSave}</button>
        <button class="btn btn-secondary btn-sm cancelar-categoria">${svgCancel}</button>
      </td>
    </tr>`);
    $('#tabla-productos tbody').prepend(filaNueva);
  });

  // Guardar nueva categoría
  $('#tabla-productos tbody').on('click', '.guardar-categoria', function () {
    let $fila = $(this).closest('tr');
    let nombre = $fila.find('input[name="nombre_categoria"]').val().trim();
    let descripcion = $fila.find('input[name="descripcion"]').val().trim();
    let estado = $fila.find('select[name="estado"]').val();
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

    if (!nombre) {
      alert('El nombre es obligatorio');
      return;
    }

    $.post('ajax/insertar_categoria.php', {
      nombre_categoria: nombre,
      descripcion: descripcion,
      estado: estado
    }, function (resp) {
      if (resp.success) {
        // ✅ Agrega la fila a DataTable
        let nuevaFila = table.row.add([
          resp.id_categoria,
          $('<div>').text(resp.nombre_categoria).html(),
          $('<div>').text(resp.descripcion).html(),
          $('<div>').text(resp.estado).html(),
          `<button class="btn btn-warning btn-sm modificar-categoria">${svgPencil}</button>`
        ]).draw(false).node();

        // ✅ Asegura que las celdas tengan clase align-middle
        $(nuevaFila).find('td').eq(0).addClass('text-center');

        // ✅ Borra fila de edición
        $fila.remove();
        editando = false;
      } else {
        alert(resp.message || 'Error al insertar la categoría');
      }
    }, 'json').fail(function () {
      alert('Error de comunicación con el servidor');
    });
  });

  // Cancelar nueva categoría
  $('#tabla-productos tbody').on('click', '.cancelar-categoria', function () {
    $(this).closest('tr').remove();
    editando = false;
  });

  // Modificar categoría
  $('#tabla-productos tbody').on('click', '.modificar-categoria', function () {
    if (editando) return;
    editando = true;
    let $fila = $(this).closest('tr');
    let tds = $fila.find('td');
    let id = tds.eq(0).text().trim();
    let nombre = tds.eq(1).text().trim();
    let descripcion = tds.eq(2).text().trim();
    let estado = tds.eq(3).text().trim();
    const svgSave = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
        <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
      </svg>`;
    const svgCancel = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
      </svg>`;
    $fila.data('original', {
      id: id,
      nombre: nombre,
      descripcion: descripcion,
      estado: estado
    });

    tds.eq(1).html(`<input type="text" class="form-control form-control-sm" name="nombre_categoria" value="${$('<div>').text(nombre).html()}" required>`);
    tds.eq(2).html(`<input type="text" class="form-control form-control-sm" name="descripcion" value="${$('<div>').text(descripcion).html()}">`);
    tds.eq(3).html(
      `<select class="form-select form-select-sm" name="estado">
        <option value="activo"${estado === 'activo' ? ' selected' : ''}>activo</option>
        <option value="inactivo"${estado === 'inactivo' ? ' selected' : ''}>inactivo</option>
      </select>`
    );
    tds.eq(4).html(
      `<button class="btn btn-success btn-sm guardar-edicion-categoria">${svgSave}</button>
       <button class="btn btn-secondary btn-sm cancelar-edicion-categoria">${svgCancel}</button>`
    );
    $fila.addClass('editando');
  });

  // Guardar edición
  $('#tabla-productos tbody').on('click', '.guardar-edicion-categoria', function () {
    let $fila = $(this).closest('tr');
    let tds = $fila.find('td');
    let id = tds.eq(0).text().trim();
    let nombre = tds.eq(1).find('input[name="nombre_categoria"]').val().trim();
    let descripcion = tds.eq(2).find('input[name="descripcion"]').val().trim();
    let estado = tds.eq(3).find('select[name="estado"]').val();
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

    if (!nombre) {
      alert('El nombre es obligatorio');
      return;
    }

    $.post('ajax/actualizar_categoria.php', {
      id_categoria: id,
      nombre_categoria: nombre,
      descripcion: descripcion,
      estado: estado
    }, function (resp) {
      if (resp.success) {
        table.row($fila).data([
          id,
          $('<div>').text(nombre).html(),
          $('<div>').text(descripcion).html(),
          $('<div>').text(estado).html(),
          `<button class="btn btn-warning btn-sm modificar-categoria">${svgPencil}</button>`
        ]).draw(false);
        $fila.removeClass('editando');
        editando = false;
      } else {
        alert(resp.message || 'Error al actualizar la categoría');
      }
    }, 'json').fail(function () {
      alert('Error de comunicación con el servidor');
    });
  });

  // Cancelar edición
  $('#tabla-productos tbody').on('click', '.cancelar-edicion-categoria', function () {
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
        $('<div>').text(original.nombre).html(),
        $('<div>').text(original.descripcion).html(),
        $('<div>').text(original.estado).html(),
        `<button class="btn btn-warning btn-sm modificar-categoria">${svgPencil}</button>`
      ]).draw(false);
      $fila.removeClass('editando');
      editando = false;
    }
  });
});
</script>
<?php include_once 'footer.php'; ?>