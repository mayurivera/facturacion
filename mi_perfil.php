<?php
include 'header.php';
if (!isset($_SESSION['id_usuario'])) {
    echo "<div class='alert alert-danger'>Debes iniciar sesión.</div>";
    include 'footer.php';
    exit;
}
?>
<div class="container mt-4">
    <h2>Mi Perfil</h2>
    <div id="perfil-content"></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(function () {
        cargarPerfil();
    });

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    function cargarPerfil() {
        $.get('perfil.php', { action: 'get' }, function (data) {
            try {
                let u = JSON.parse(data);
                let html = `
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">${escapeHtml(u.nombre)}</h5>
                            <p><strong>Correo:</strong> ${escapeHtml(u.correo)}</p>
                            <p><strong>Rol:</strong> ${escapeHtml(u.nombre_rol)}</p>
                            <p><strong>Estado:</strong> <span class="badge bg-${u.estado === 'activo' ? 'success' : 'secondary'}">${escapeHtml(u.estado)}</span></p>
                            <button class="btn btn-warning" onclick="mostrarFormPerfil(${u.id_usuario})">Editar Perfil</button>
                        </div>
                    </div>
                    <div id="form-perfil"></div>
                `;
                $('#perfil-content').html(html);
            } catch (e) {
                $('#perfil-content').html("<div class='alert alert-danger'>Error al cargar perfil.</div>");
            }
        });
    }

    window.mostrarFormPerfil = function (id) {
        $.get('perfil.php', { action: 'get' }, function (data) {
            try {
                let u = JSON.parse(data);
                $('#form-perfil').html(`
                    <form id="perfilForm" class="card card-body shadow-sm" enctype="multipart/form-data">
                        <div class="mb-3 text-center">
                            <img id="previewFoto" src="assets/img/users/${u.foto ? u.foto : u.id_usuario + '.jpg'}?t=${Date.now()}" 
                                onerror="this.src='assets/img/user-default.png'" 
                                class="rounded-circle mb-2" style="width:90px;height:90px;object-fit:cover;">
                            <div>
                                <input type="file" class="form-control mt-2" name="foto" accept="image/*" onchange="previewFotoPerfil(event)">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Nombre completo</label>
                            <input type="text" class="form-control" name="nombre" value="${escapeHtml(u.nombre)}" required>
                        </div>
                        <div class="mb-2">
                            <label>Correo</label>
                            <input type="email" class="form-control" name="correo" value="${escapeHtml(u.correo)}" required>
                        </div>
                        <div class="mb-2">
                            <label>Nueva contraseña <small>(dejar en blanco para no cambiar)</small></label>
                            <input type="password" class="form-control" name="contraseña" placeholder="********">
                        </div>
                        <button class="btn btn-success" type="submit">Guardar cambios</button>
                        <button class="btn btn-secondary" type="button" onclick="$('#form-perfil').html('')">Cancelar</button>
                    </form>
                `);
                $('#perfilForm').on('submit', function (e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $.ajax({
                        url: 'perfil.php?action=save',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (resp) {
                            try {
                                let r = JSON.parse(resp);
                                if (r.success) {
                                    cargarPerfil();
                                    alert("Perfil actualizado correctamente.");
                                    // Forzar recarga de la imagen del navbar (evita caché)
                                    let id_usuario = <?php echo json_encode($_SESSION['id_usuario']); ?>;
                                    let fotoUrlJpg = 'assets/img/users/' + id_usuario + '.jpg?t=' + Date.now();
                                    let fotoUrlPng = 'assets/img/users/' + id_usuario + '.png?t=' + Date.now();

                                    var navbarImg = document.getElementById('navbarFotoPerfil');
                                    if (navbarImg) {
                                        // Probar primero JPG, si falla probar PNG, si falla mostrar default
                                        navbarImg.onerror = function() {
                                            if (navbarImg.src.indexOf('.jpg') !== -1) {
                                                navbarImg.src = fotoUrlPng;
                                            } else {
                                                navbarImg.src = 'assets/img/users/user-default.png';
                                            }
                                        };
                                        navbarImg.src = fotoUrlJpg;
                        }
                                } else {
                                    alert(r.message || 'Error al actualizar perfil.');
                                }
                            } catch (e) {
                                alert("Error inesperado.");
                            }
                        }
                    });
                });
            } catch (e) {
                $('#form-perfil').html("<div class='alert alert-danger'>Error al cargar datos del formulario.</div>");
            }
        });
    };

    window.previewFotoPerfil = function(event) {
        const [file] = event.target.files;
        if (file) {
            $('#previewFoto').attr('src', URL.createObjectURL(file));
        }
    };
</script>
<?php include 'footer.php'; ?>