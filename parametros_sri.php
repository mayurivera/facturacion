<?php include 'header.php'; ?>
<div class="container mt-4">
    <h2>Parámetros SRI</h2>
    <div id="parametros-content"></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {
    cargarParametrosSRI();
});

function toggleEstado(id, estadoActual) {
    const nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';

    $.post('param_sri.php', { action: 'estado', id: id, estado: nuevoEstado }, function (resp) {
        if (resp.success) {
            cargarParametrosSRI(); // recarga la tabla para reflejar el cambio
        } else {
            alert('No se pudo cambiar el estado');
        }
    }, 'json');
}

function cargarParametrosSRI() {
    $.get('param_sri.php', { action: 'list' }, function (parametros) {
        let html = `
            <button class="btn btn-primary mb-2" onclick="mostrarFormParametro()">Nuevo Parámetro</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th><th>Ambiente</th><th>RUC</th><th>Razón Social</th>
                        <th>Dominio</th><th>URL Autorización</th><th>URL Recepción</th>
                        <th>Estado</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${parametros.map(p => `
                        <tr>
                            <td>${p.id_parametro}</td>
                            <td>${p.ambiente}</td>
                            <td>${p.ruc_emisor}</td>
                            <td>${p.razon_social}</td>
                            <td>${p.ambiente === 'produccion' ? p.dominio_produccion : p.dominio_pruebas}</td>
                            <td>${(p.ambiente === 'produccion' ? p.dominio_produccion : p.dominio_pruebas) + p.url_autorizacion}</td>
                            <td>${(p.ambiente === 'produccion' ? p.dominio_produccion : p.dominio_pruebas) + p.url_recepcion}</td>
                            <td>
                                <button 
                                    class="btn btn-sm estado-btn ${p.estado_registro === 'activo' ? 'btn-success' : 'btn-danger'}" 
                                    onclick="toggleEstado(${p.id_parametro}, '${p.estado_registro}')">
                                    ${p.estado_registro === 'activo' ? 'Activo' : 'Inactivo'}
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editarParametro(${p.id_parametro})">Editar</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <div id="form-parametro"></div>
        `;
        $('#parametros-content').html(html);
    });
}

function mostrarFormParametro(param = {}) {
    $('#form-parametro').html(`
        <form id="parametroForm" class="mt-3">
            <input type="hidden" name="id_parametro" value="${param.id_parametro || ''}">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label>Ambiente</label>
                    <select class="form-control" name="ambiente" required>
                        <option value="pruebas" ${param.ambiente === 'pruebas' ? 'selected' : ''}>Pruebas</option>
                        <option value="produccion" ${param.ambiente === 'produccion' ? 'selected' : ''}>Producción</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>RUC Emisor</label>
                    <input type="text" class="form-control" name="ruc_emisor" value="${param.ruc_emisor || ''}" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Razón Social</label>
                    <input type="text" class="form-control" name="razon_social" value="${param.razon_social || ''}" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Nombre Comercial</label>
                    <input type="text" class="form-control" name="nombre_comercial" value="${param.nombre_comercial || ''}">
                </div>
                <div class="col-md-4 mb-2">
                    <label>Dominio Producción</label>
                    <input type="text" class="form-control" name="dominio_produccion" value="${param.dominio_produccion || 'https://cel.sri.gob.ec'}">
                </div>
                <div class="col-md-4 mb-2">
                    <label>Dominio Pruebas</label>
                    <input type="text" class="form-control" name="dominio_pruebas" value="${param.dominio_pruebas || 'https://pruebas.sri.gob.ec'}">
                </div>
                <div class="col-md-6 mb-2">
                    <label>URL Autorización</label>
                    <input type="text" class="form-control" name="url_autorizacion" value="${param.url_autorizacion || ''}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label>URL Recepción</label>
                    <input type="text" class="form-control" name="url_recepcion" value="${param.url_recepcion || ''}" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Origen Dato</label>
                    <input type="text" class="form-control" name="origen_dato" value="${param.origen_dato || ''}">
                </div>
            </div>
            <button class="btn btn-success" type="submit">Guardar</button>
            <button class="btn btn-secondary" type="button" onclick="$('#form-parametro').html('')">Cancelar</button>
        </form>
    `);

    $('#parametroForm').off('submit').on('submit', function (e) {
        e.preventDefault();
        $.post('param_sri.php?action=save', $(this).serialize(), function (response) {
            if (response.success) {
                cargarParametrosSRI();
                $('#form-parametro').html('');
            } else {
                alert('Error al guardar parámetro');
            }
        }, 'json');
    });
}

function editarParametro(id) {
    $.get('param_sri.php', { action: 'get', id }, function (data) {
        if (data && Object.keys(data).length > 0) {
            mostrarFormParametro(data);
        } else {
            alert('Parámetro no encontrado');
        }
    }, 'json');
}

function cambiarEstado(id, estado) {
    $.post('param_sri.php', { action: 'estado', id, estado }, function (resp) {
        if (!resp.success) {
            alert('No se pudo cambiar el estado');
        }
    }, 'json');
}
</script>

<?php include 'footer.php'; ?>