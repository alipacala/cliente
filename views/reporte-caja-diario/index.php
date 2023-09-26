<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); $editar = isset($_GET["id"]) ?
$_GET["id"] : false; ?>

<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">Reporte diario de caja</h2>
    </div>
    <div class="card-body">
      <form id="form-reporte">
        <div class="row mb-3">
          <div class="form-group col-md-4 mx-auto">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha"
            value="<?php echo date("Y-m-d") ?>" required />
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 mx-auto">
            <div class="row">
              <input
                type="submit"
                class="col-6 btn btn-primary"
                id="mostrar-cerrar-turno"
                value="Ok"
              />
              <a class="col-6 btn btn-warning" href="./../">Salir</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="noti"
  tabindex="-1"
  aria-labelledby="noti-label"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="noti-label">Cerrar el turno</h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">¿Quiere cerrar el turno?</div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-primary"
          id="no-cerrar-turno-1"
          data-bs-dismiss="modal"
        >
          Todavía NO
        </button>
        <button
          type="button"
          class="btn btn-outline-danger"
          id="cerrar-turno-1"
          data-bs-dismiss="modal"
        >
          Sí
        </button>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="noti2"
  tabindex="-1"
  aria-labelledby="noti2-label"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="noti2-label">
          Confirmar cerrar el turno
        </h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">¿Realmente quiere cerrar el turno?</div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-danger"
          id="cerrar-turno-2"
          data-bs-dismiss="modal"
        >
          Sí
        </button>
        <button
          type="button"
          class="btn btn-outline-secondary"
          id="no-cerrar-turno-2"
          data-bs-dismiss="modal"
        >
          No
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";
  const apiRecibosUrl = "<?php echo URL_API_NUEVA ?>/recibos-pago";

  function wrapper() {
    mostrarAlertaSiHayMensaje();

    prepararBotonGenerar();
    prepararBotonCerrarTurno1();
    prepararBotonesNoCerrarTurno();
    prepararBotonAceptar();
  }

  function prepararBotonGenerar() {
    const botonCerrarTurno = document.getElementById("mostrar-cerrar-turno");
    botonCerrarTurno.addEventListener("click", function (event) {
      event.preventDefault();
      const noti = new bootstrap.Modal(document.getElementById("noti"));
      noti.show();
    });
  }

  function prepararBotonCerrarTurno1() {
    const botonCerrarTurno = document.getElementById("cerrar-turno-1");
    botonCerrarTurno.addEventListener("click", async function (event) {
      event.preventDefault();
      const noti = new bootstrap.Modal(document.getElementById("noti"));
      noti.hide();

      const noti2 = new bootstrap.Modal(document.getElementById("noti2"));
      noti2.show();
    });
  }

  function prepararBotonAceptar() {
    const botonAceptar = document.getElementById("cerrar-turno-2");
    botonAceptar.addEventListener("click", async function (event) {
      event.preventDefault();

      const url = `${apiRecibosUrl}/0/cerrar-turno`;
      const options = {
        method: "PATCH",
      };
      try {
        const response = await fetch(url, options);
        if (!response.ok) {
          throw new Error(response.statusText);
        }

        generarReporte();
      } catch (error) {
        console.error(error);
        mostrarAlert("error", "No se pudo cerrar el turno", "editar");
      }
    });
  }

  function prepararBotonesNoCerrarTurno() {
    const botonNoCerrarTurno1 = document.getElementById("no-cerrar-turno-1");
    const botonNoCerrarTurno2 = document.getElementById("no-cerrar-turno-2");

    const alNoCerrarTurno = (e) => {
      event.preventDefault();
      generarReporte();
    };

    botonNoCerrarTurno1.addEventListener("click", alNoCerrarTurno);
    botonNoCerrarTurno2.addEventListener("click", alNoCerrarTurno);
  }

  function generarReporte() {
    const fecha = document.getElementById("fecha").value;
    const url = `${apiReportesUrl}?tipo=caja&fecha=${fecha}`;
    open(url, "_blank");
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
