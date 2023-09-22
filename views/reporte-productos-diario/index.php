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
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">Reporte diario de ventas al detalle</h2>
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
                id="generar-reporte"
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

<script>
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";

  function wrapper() {
    prepararBotonGenerar();
  }

  function prepararBotonGenerar() {
    const botonCerrarTurno = document.getElementById("generar-reporte");
    botonCerrarTurno.addEventListener("click", function (event) {
      event.preventDefault();
      const fecha = document.getElementById("fecha").value;
      if (fecha) {
        generarReporte(fecha);
      } else {
        alert("Debe ingresar una fecha");
      }
    });
  }

  function generarReporte(fecha) {
    const url = `${apiReportesUrl}?tipo=detalles&fecha=${fecha}`;
    open(url, "_blank");
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
