<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if (
  $tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD) ) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false; $idUsuario =
$_SESSION["usuario"]["id_usuario"]; mostrarHeader("pagina-funcion", $logueado);
$prePath = ENV == 'server' ? '/hotelarenasspa/cliente' : '/cliente'; ?>
<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card w-50 mx-auto" id="card-login">
    <div class="card-header py-3">
      <h2 class="h3 fw-normal text-center">
        Inicio de sesión de colaboradores
      </h2>
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
        <div class="form-group mb-3">
          <label for="usuario">Nombre de usuario</label>
          <input
            type="text"
            class="form-control"
            id="usuario"
            placeholder="Ingrese su nombre de usuario"
          />
        </div>
        <div class="form-group mb-3">
          <label for="contraseña">Contraseña</label>
          <input
            type="password"
            class="form-control"
            id="contraseña"
            placeholder="Ingrese su contraseña"
          />
        </div>
        <button class="btn btn-primary w-100 py-2" onclick="loginColaborador()">
          Iniciar sesión
        </button>
      </main>
    </div>
  </div>

  <div class="card d-none" id="card-menu">
    <div class="card-header py-3">
      <h2 class="h3 fw-normal text-center">
        Inicio de sesión de colaboradores
      </h2>
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
  const apiUsuariosUrl = "<?php echo URL_API_NUEVA ?>/usuarios";

  let usuarioEl = null;
  let contraseñaEl = null;

  let productos = [];
  let terapistas = [];
  let acompanantes = [];

  let productoSeleccionado = null;
  let cantidadSeleccionada = null;

  let iterador = 1;
  let detalles = [];

  async function wrapper() {
    usuarioEl = document.getElementById("usuario");
    contraseñaEl = document.getElementById("contraseña");
  }

  function pasarDeLoginAlMenu() {
    document.querySelector("#card-login").classList.add("d-none");
    document.querySelector("#card-menu").classList.remove("d-none");
  }

  async function loginColaborador() {
    const usuario = usuarioEl.value;
    const contrasena = contraseñaEl.value;

    if (usuario == "" || contrasena == "") {
      mostrarAlert("error", "Ingrese usuario y contraseña", "login");
      return;
    }

    const url = `${apiUsuariosUrl}/login-colaboradores`;
    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ usuario, contrasena }),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);

      if (data) {
        idUsuario = data.resultado.id_usuario;
        pasarDeLoginAlMenu();
      } else {
        mostrarAlert("error", "Usuario o contraseña incorrectos", "login");
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al iniciar sesión", "login");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
