<?php include 'header.php'; ?>
<div class="container mt-4">
    <h2>Gestión de Roles y Usuarios</h2>
    <ul class="nav nav-tabs" id="tabMenu">
        <li class="nav-item">
            <a class="nav-link active" id="roles-tab" data-bs-toggle="tab" href="#roles">Roles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="usuarios-tab" data-bs-toggle="tab" href="#usuarios">Usuarios</a>
        </li>
    </ul>
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="roles">
            <div id="roles-content"></div>
        </div>
        <div class="tab-pane fade" id="usuarios">
            <div id="usuarios-content"></div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<?php include 'footer.php'; ?>

<script>
    $(function () {
        cargarRoles();
        cargarUsuarios();

        $('#roles-tab').on('click', cargarRoles);
        $('#usuarios-tab').on('click', cargarUsuarios);
    });

    // ----- ROLES -----
    function cargarRoles() {
        $.get('roles.php', { action: 'list' }, function (data) {
            let roles = JSON.parse(data);
            let html = `
                <button class="btn btn-primary mb-2" onclick="mostrarFormRol()">Nuevo Rol</button>
                <table class="table table-bordered">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody>
                        ${roles.map(rol => `
                            <tr>
                                <td>${rol.id_rol}</td>
                                <td>${rol.nombre_rol}</td>
                                <td>${rol.descripcion || ''}</td>
                                <td>${rol.estado}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editarRol(${rol.id_rol})">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="deshabilitarRol(${rol.id_rol})">Deshabilitar</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                <div id="form-rol"></div>
            `;
            $('#roles-content').html(html);
        });
    }

    window.mostrarFormRol = function (rol = {}) {
        $('#form-rol').html(`
            <form id="rolForm" class="mt-3">
                <input type="hidden" name="id_rol" value="${rol.id_rol || ''}">
                <input type="text" class="form-control mb-2" name="nombre_rol" placeholder="Nombre del rol" value="${rol.nombre_rol || ''}" required>
                <input type="text" class="form-control mb-2" name="descripcion" placeholder="Descripción" value="${rol.descripcion || ''}">
                <select class="form-control mb-2" name="estado">
                    <option value="activo" ${rol.estado === 'activo' ? 'selected' : ''}>Activo</option>
                    <option value="inactivo" ${rol.estado === 'inactivo' ? 'selected' : ''}>Inactivo</option>
                </select>
                <button class="btn btn-success" type="submit">Guardar</button>
                <button class="btn btn-secondary" type="button" onclick="$('#form-rol').html('')">Cancelar</button>
            </form>
        `);
        $('#rolForm').on('submit', function (e) {
            e.preventDefault();
            $.post('roles.php?action=save', $(this).serialize(), function () {
                cargarRoles();
            });
        });
    };

    window.editarRol = function (id) {
        $.get('roles.php', { action: 'get', id: id }, function (data) {
            mostrarFormRol(JSON.parse(data));
        });
    };

    window.deshabilitarRol = function (id) {
        if (confirm('¿Deshabilitar este rol?')) {
            $.get('roles.php', { action: 'disable', id: id }, function () {
                cargarRoles();
            });
        }
    };

    // ----- USUARIOS -----
    function cargarUsuarios() {
        $.get('usuarios.php', { action: 'list' }, function (data) {
            let usuarios = JSON.parse(data);
            let html = `
                <button class="btn btn-primary mb-2" onclick="mostrarFormUsuario()">Nuevo Usuario</button>
                <table class="table table-bordered">
                    <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
                    <tbody>
                        ${usuarios.map(u => `
                            <tr>
                                <td>${u.id_usuario}</td>
                                <td>${u.nombre}</td>
                                <td>${u.correo}</td>
                                <td>${u.nombre_rol}</td>
                                <td>${u.estado}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editarUsuario(${u.id_usuario})">Editar</button>
                                    <button class="btn btn-sm btn-danger" onclick="deshabilitarUsuario(${u.id_usuario})">Deshabilitar</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                <div id="form-usuario"></div>
            `;
            $('#usuarios-content').html(html);
        });
    }

    window.mostrarFormUsuario = function (usuario = {}) {
        $.get('roles.php', { action: 'list' }, function (data) {
            let roles = JSON.parse(data);
            $('#form-usuario').html(`
                <form id="usuarioForm" class="mt-3">
                    <input type="hidden" name="id_usuario" value="${usuario.id_usuario || ''}">
                    <input type="text" class="form-control mb-2" name="nombre" placeholder="Nombre completo" value="${usuario.nombre || ''}" required>
                    <input type="email" class="form-control mb-2" name="correo" placeholder="Correo" value="${usuario.correo || ''}" required>
                    <input type="password" class="form-control mb-2" name="contraseña" placeholder="Contraseña" ${usuario.id_usuario ? '' : 'required'}>
                    <select class="form-control mb-2" name="id_rol" required>
                        <option value="">Seleccione rol</option>
                        ${roles.map(r => `<option value="${r.id_rol}" ${usuario.id_rol == r.id_rol ? 'selected' : ''}>${r.nombre_rol}</option>`).join('')}
                    </select>
                    <select class="form-control mb-2" name="estado">
                        <option value="activo" ${usuario.estado === 'activo' ? 'selected' : ''}>Activo</option>
                        <option value="inactivo" ${usuario.estado === 'inactivo' ? 'selected' : ''}>Inactivo</option>
                        <option value="suspendido" ${usuario.estado === 'suspendido' ? 'selected' : ''}>Suspendido</option>
                    </select>
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button class="btn btn-secondary" type="button" onclick="$('#form-usuario').html('')">Cancelar</button>
                </form>
            `);
            $('#usuarioForm').on('submit', function (e) {
                e.preventDefault();
                $.post('usuarios.php?action=save', $(this).serialize(), function () {
                    cargarUsuarios();
                });
            });
        });
    };

    window.editarUsuario = function (id) {
        $.get('usuarios.php', { action: 'get', id: id }, function (data) {
            mostrarFormUsuario(JSON.parse(data));
        });
    };

    window.deshabilitarUsuario = function (id) {
        if (confirm('¿Deshabilitar este usuario?')) {
            $.get('usuarios.php', { action: 'disable', id: id }, function () {
                cargarUsuarios();
            });
        }
    };
</script>
