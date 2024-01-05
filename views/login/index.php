<?php
require "../../inc/header.php";
require "../../db/auth.php";

$metodo = $_SERVER['REQUEST_METHOD'];

$pre = ENV == 'server' ? '/hotelarenasspa/cliente' : './cliente';

if ($metodo == 'POST') {
  $usuario = $_POST['usuario'];
  $clave = $_POST['clave'];
  iniciarSesion($usuario, $clave);
}

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("", $logueado);
?>
<div class="container main-cont">
  <div class="card w-50 mx-auto">
    <div class="card-header py-3">
      <h2 class="h3 fw-normal text-center">Inicio de sesión</h2>
    </div>
    <div class="card-body">
      <main class="form-signin w-100 m-auto">
        <form id="form-login" method="POST" action="#">
          <div class="row">
            <img src="<?php echo $pre ?>/img/logo.webp" alt="logo" class="d-inline-block align-text-top img-fluid w-25 mb-3 mx-auto">
          </div>
          <div class="form-group mb-3">
            <label for="usuario">Nombre de usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su nombre de usuario">
          </div>
          <div class="form-group mb-3">
            <label for="clave">Clave</label>
            <input type="password" class="form-control" id="clave" name="clave" placeholder="Ingrese su clave">
          </div>
          <div class="form-check text-start mb-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault"> Recuerda mi usuario </label>
          </div>
          <button class="btn btn-primary w-100 py-2" type="submit">Iniciar sesión</button>
        </form>
      </main>
    </div>
  </div>
</div>
<?php
require "../../inc/footer.php";
?>