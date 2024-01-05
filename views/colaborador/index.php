<?php
require "../../inc/header.php";
require "../../db/auth.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
  $usuario = $_POST['usuario'];
  $clave = $_POST['clave'];
  iniciarSesionColaborador($usuario, $clave);
}
  
session_start();

$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("colaborador");

$prePath = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente';
?>
<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card w-50 mx-auto" id="card-login">
    <div class="card-header py-3">
      <h2 class="h3 fw-normal text-center">Inicio de sesión de colaborador</h2>
    </div>
    <div class="card-body">
      <main class="form-signin w-100 m-auto">
        <div class="row">
          <img
            src="<?php echo $prePath ?>/img/logo.webp"
            alt="logo"
            class="d-inline-block align-text-top img-fluid w-25 mb-3 mx-auto"
          />
        </div>
        <form id="form-login" method="POST" action="#">
          <div class="form-group mb-3">
            <label for="usuario">Nombre de usuario</label>
            <input
              type="text"
              class="form-control"
              id="usuario"
              name="usuario"
              placeholder="Ingrese su nombre de usuario"
            />
          </div>
          <div class="form-group mb-3">
            <label for="contraseña">Contraseña</label>
            <input
              type="password"
              class="form-control"
              id="contraseña"
              name="clave"
              placeholder="Ingrese su contraseña"
            />
          </div>
          <button class="btn btn-primary w-100 py-2" type="submit">
            Iniciar sesión
          </button>
        </form>
      </main>
    </div>
  </div>

  <div class="card d-none" id="card-menu">
    <div class="card-header py-3">
      <h2 class="h3 fw-normal text-center">Opciones de colaborador</h2>
    </div>
    <div class="card-body">
      <ul>
        <li>
          <img src="<?php echo $prePath ?>/img/logo.webp" alt="" /><a
            href="./../toma-pedidos"
            >Toma de pedidos</a
          >
        </li>
        <li>
          <img src="<?php echo $prePath ?>/img/logo.webp" alt="" /><a
            href="./../registro-desayunos"
            >Registro de Desayunos</a
          >
        </li>
        <li>
          <img src="<?php echo $prePath ?>/img/logo.webp" alt="" /><a
            href="./../mantenimiento-habitaciones"
            >Mantenimiento Habitaciones</a
          >
        </li>
        <li>
          <img src="<?php echo $prePath ?>/img/logo.webp" alt="" /><a
            href="./../registro-inventario"
            >Registro Inventario</a
          >
        </li>
      </ul>
    </div>
  </div>
</div>

<script>
  async function wrapper() {
    mostrarAlertaSiHayMensaje();
    const logueado = "<?php echo $logueado; ?>";
    console.log(logueado);

    if (logueado) {
      document.getElementById("card-login").classList.add("d-none");
      document.getElementById("card-menu").classList.remove("d-none");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
