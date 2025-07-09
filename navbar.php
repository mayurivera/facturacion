<?php
$id_usuario = $_SESSION['id_usuario'];
$foto_jpg = "assets/img/users/{$id_usuario}.jpg";
$foto_png = "assets/img/users/{$id_usuario}.png";
$foto = file_exists($foto_jpg) ? $foto_jpg : (file_exists($foto_png) ? $foto_png : "assets/img/users/user-default.png");
?>

<nav class="navbar navbar-expand-lg bg-body px-3">
  <div class="ms-auto dropdown">
    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
      <img id="navbarFotoPerfil"
      src="<?php echo $foto; ?>"
      alt="Foto de perfil"
      class="rounded-circle me-2"
      style="width:36px;height:36px;object-fit:cover;">
        <strong><?php echo $_SESSION["nombre"]; ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
        <li><a class="dropdown-item" href="mi_perfil.php">Ver perfil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="logout.php">Cerrar sesión</a></li>
    </ul>
  </div>
</nav>