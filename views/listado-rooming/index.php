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
              onchange="buscarPorFecha()"
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

<div
  class="modal modal fade"
  id="modal-cambio-habitacion"
  tabindex="-1"
  aria-labelledby="modal-cambio-habitacion-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-cambio-habitacion-label">
          Cambio de habitación
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-cambio-habitacion"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row mt-3">
          <div class="col-md-6 mb-3">
            <label for="nro_registro_maestro">Nro de Registro Maestro:</label>
            <input
              type="text"
              class="form-control"
              id="nro_registro_maestro"
              disabled
            />
          </div>
          <div class="col-md-6 mb-3">
            <label for="nombre_cliente">Nombre del cliente:</label>
            <input
              type="text"
              class="form-control"
              id="nombre_cliente"
              disabled
            />
          </div>
          <div class="col-md-12 mb-3 d-flex align-items-center">
            <div class="col-auto d-flex align-items-center">
              <span>Desde:</span>
            </div>
            <div class="col-auto mx-2">
              <input type="date" class="form-control" id="fecha_in" disabled />
            </div>
            <div class="col-auto d-flex align-items-center">hasta el:</div>
            <div class="col-auto mx-2">
              <input type="date" class="form-control" id="fecha_out" disabled />
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="nro_habitacion_anterior"
              >Nro de habitación anterior:</label
            >
            <input
              type="text"
              class="form-control"
              id="nro_habitacion_anterior"
              disabled
            />
          </div>
          <div class="col-md-6 mb-3">
            <label for="nro_habitacion">Nro de habitación a cambiar:</label>
            <select class="form-select" id="nro_habitacion"></select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-6">
          <div class="d-flex justify-content-end">
            <button
              type="button"
              class="btn btn-primary me-2"
              id="cambiar-habitacion"
              onclick="cambiarHabitacion()"
            >
              Aceptar
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Salir
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiRoomingUrl = "<?php echo URL_API_NUEVA ?>/rooming";
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiHabitacionesUrl = "<?php echo URL_API_NUEVA ?>/habitaciones";

  let fechaBusqueda = null;
  let selectHabitaciones = null;

  let nroHabitacionEl = null;
  let nroRegistroMaestroEl = null;
  let nombreClienteEl = null;
  let fechaInEl = null;
  let fechaOutEl = null;

  let tablaRooming = null;
  let modalCambioHabitacion = null;

  let datosCheckin = null;

  function wrapper() {
    fechaBusqueda = document.getElementById("fecha_busqueda");
    selectHabitaciones = document.getElementById("nro_habitacion");

    nroHabitacionEl = document.getElementById("nro_habitacion_anterior");
    nroRegistroMaestroEl = document.getElementById("nro_registro_maestro");
    nombreClienteEl = document.getElementById("nombre_cliente");
    fechaInEl = document.getElementById("fecha_in");
    fechaOutEl = document.getElementById("fecha_out");

    tablaRooming = document.getElementById("rooming").tBodies[0];
    modalCambioHabitacion = new bootstrap.Modal(
      document.getElementById("modal-cambio-habitacion")
    );

    buscarPorFecha();
  }

  async function cambiarHabitacion() {
    const nroHabitacion = selectHabitaciones.value;

    const data = {
      nro_habitacion: nroHabitacion,
      prev_nro_habitacion: datosCheckin.nro_habitacion,
      fecha: fechaBusqueda.value,
    };

    const url = `${apiCheckingsUrl}/${datosCheckin.id}/habitacion`;
    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);

      modalCambioHabitacion.hide();
      buscarPorFecha();
    } catch (error) {
      console.error(error);
    }
  }

  async function cargarHabitaciones() {
    const url = `${apiHabitacionesUrl}?con-disponibilidad&fecha=${fechaBusqueda.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      selectHabitaciones.innerHTML = "";

      data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item.nro_habitacion;
        option.innerText = item.nro_habitacion;
        selectHabitaciones.appendChild(option);
      });
    } catch (error) {
      console.error(error);
    }

    modalCambioHabitacion.show();
    buscarPorFecha();
  }

  async function buscarPorFecha() {
    const url = `${apiRoomingUrl}?con-datos&fecha=${fechaBusqueda.value}`;

    try {
      const response = await fetch(url);
      const data = await response.json();

      console.log(data);

      tablaRooming.innerHTML = "";
      data.forEach((item) => {
        const row = tablaRooming.insertRow();

        const fechaActual = new Date().toISOString().split("T")[0];
        const fechaSeleccionadaEsFuturaUHoy = fechaBusqueda.value >= fechaActual;

        row.dataset.id = item.id_checkin;
        row.dataset.nro_habitacion = item.nro_habitacion;
        row.dataset.nro_registro_maestro = item.nro_registro_maestro;
        row.dataset.nombre_cliente = item.nombre;
        row.dataset.fecha_out = item.fecha_out;

        row.classList.add(item.de_salida ? "de_salida" : item.ocupado ? "ocupado" : item.reservado ? "reservado" : "libre");

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
            ${
              fechaSeleccionadaEsFuturaUHoy && item.nro_registro_maestro
                ? `<a href="../gestionar-checkin-hotel?id_checkin=${item.id_checkin}&nro_habitacion=${item.nro_habitacion}" class="btn btn-warning" style="--bs-btn-padding-y: .25rem;">EDITAR</a><button id="cambiar-habitacion" class="btn btn-secondary" onclick="prepararCambiarHabitacion(event)">CAMBIAR HAB</button>`
                : ""
            }
          </td>
      `;
      });
    } catch (error) {
      console.error(error);
    }
  }

  function prepararCambiarHabitacion(event) {
    datosCheckin = event.target.closest("tr").dataset;

    nroHabitacionEl.value = datosCheckin.nro_habitacion;
    nroRegistroMaestroEl.value = datosCheckin.nro_registro_maestro;
    nombreClienteEl.value = datosCheckin.nombre_cliente;
    fechaInEl.value = fechaBusqueda.value;
    fechaOutEl.value = datosCheckin.fecha_out;

    cargarHabitaciones();
    modalCambioHabitacion.show();
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
