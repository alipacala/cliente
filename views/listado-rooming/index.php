<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

<div class="container my-5 main-cont">
  <div class="card">
    <div class="card-body">
      <h3 class="mb-4">LISTADO DE ROOMIN - REGISTRO DE HUESPEDES</h3>
      <div class="table-responsive">
        <div class="col-md-4">
          <label for="validationCustom02" class="form-label"
            >Buscar por fecha:</label
          >
          <div class="input-group mb-3">
            <input
              type="date"
              class="form-control"
              id="fecha_busqueda"
              value="<?php echo date('Y-m-d'); ?>"
            />

            <button
              class="btn btn-success"
              type="button"
              onclick="buscarPorFecha()"
            >
              Buscar
            </button>
          </div>
        </div>
        <table id="rooming" class="table">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Nro Habitacion</th>
              <th>Nro. Reg. Maestro</th>
              <th>Nro Reserva</th>
              <th>Cliente</th>
              <th>Nro Personas</th>
              <th>Fecha Llegada</th>
              <th>Fecha Salida</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody id="pendiente-pago"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  const apiRoomingUrl = "<?php echo URL_API_NUEVA ?>/rooming";

  let fechaBusqueda = null;
  let tablaRooming = null;

  function wrapper() {
    fechaBusqueda = document.getElementById("fecha_busqueda");
    tablaRooming = document.getElementById("rooming").tBodies[0];

    buscarPorFecha();
  }

  async function buscarPorFecha() {
    const url = `${apiRoomingUrl}?con-datos&fecha=${fechaBusqueda.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      tablaRooming.innerHTML = "";
      data.forEach((item) => {
        const row = tablaRooming.insertRow();
        row.innerHTML = `
          <td>${item.nombre_producto || ""}</td>
          <td>${item.nro_habitacion || ""}</td>
          <td>${item.nro_registro_maestro || ""}</td>
          <td>${item.nro_reserva || ""}</td>
          <td>${item.nombre || ""}</td>
          <td>${item.nro_personas || ""}</td>
          <td>${item.fecha_in || ""}</td>
          <td>${item.fecha_out || ""}</td>
          <td>
            ${item.nro_registro_maestro ? `<a href="../gestionar-checkin-hotel?id_checkin=${
              item.id_checkin
            }" class="btn btn-warning" style="--bs-btn-padding-y: .25rem;">EDITAR</a>` : ""}
          </td>
      `;
      });
    } catch (error) {
      console.error(error);
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
