<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); ?>

<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">
        Formulario de Relación de Clientes en el Hotel Spa
      </h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">Fecha: <span id="fecha" class="fw-bold"></span></div>
        <div class="col-md-3">Hora: <span id="hora" class="fw-bold"></span></div>

        <div class="col-md-6">
          <div class="form-check form-switch">
            <input
              class="form-check-input"
              type="checkbox"
              id="checkings-cerrados"
              onchange="cargarChekings()"
            />
            <label class="form-check-label" for="checkings-cerrados">
              Mostrar checkings cerrados
            </label>
          </div>
      </div>

      <div class="table-responsive">
        <table
          id="tabla-clientes"
          class="table table-bordered text-center table-hover table-striped"
        >
          <thead>
            <tr>
              <th>Tipo serv</th>
              <th>Cliente</th>
              <th>Nro. Personas</th>
              <th>Tiempo</th>
              <th>Fecha In.</th>
              <th>Hora In.</th>
              <th>Fecha Out.</th>
              <th>Estado</th>
              <th>CTA</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div class="d-flex">
        <div class="me-4">Niños: <span id="ninos" class="fw-bold"></span></div>
        <div class="me-4">
          Adultos: <span id="adultos" class="fw-bold"></span>
        </div>
        <div class="me-4">PAX: <span id="pax" class="fw-bold"></span></div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiChekingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiRoomingUrl = "<?php echo URL_API_NUEVA ?>/rooming";

  async function wrapper() {
    mostrarAlertaSiHayMensaje();
    
    await cargarChekings();
  }

  async function cargarChekings() {
    limpiarTabla();
    actualizarFechaHora();

    const cerrados = document.getElementById("checkings-cerrados").checked;

    const url = `${apiChekingsUrl}?${cerrados ? "cerrados" : "abiertos"}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      const tbody = document.getElementById("tabla-clientes").tBodies[0];

      const chekings = data.sort((a, b) => {
        if (a.tipo_de_servicio == "SPA") {
          return -1;
        } else if (b.tipo_de_servicio == "SPA") {
          return 1;
        } else {
          return 0;
        }
      })

      chekings.forEach(async (cheking) => {
        const tr = tbody.insertRow();

        const tdTipoServicio = tr.insertCell();

        if (cheking.tipo_de_servicio == "HOTEL") {
          tdTipoServicio.innerText = cheking.nro_habitacion
            ? `H ${cheking.nro_habitacion}`
            : "HOTEL";
        } else {
          tdTipoServicio.innerText = cheking.tipo_de_servicio;
        }

        const tdCliente = tr.insertCell();
        tdCliente.innerText = cheking.nombre;
        tdCliente.classList.add("text-start");
        const tdNroPersonas = tr.insertCell();
        tdNroPersonas.innerText = obtenerStringNroPersonas(cheking);
        const tdTiempo = tr.insertCell();
        tdTiempo.innerText = obtenerTiempoTranscurrido(cheking);
        const tdFechaIn = tr.insertCell();
        tdFechaIn.innerText = cheking.fecha_in;
        const tdHoraIn = tr.insertCell();
        tdHoraIn.innerText = cheking.hora_in;
        const tdFechaOut = tr.insertCell();
        tdFechaOut.innerText = cheking.fecha_out;
        const tdEstado = tr.insertCell();
        tdEstado.innerText = ""; // TODO: pendiente de implementar

        const tdCta = tr.insertCell();

        const botonVerCuenta = document.createElement("button");
        botonVerCuenta.innerText = "Ver Cuenta";
        botonVerCuenta.classList.add("btn", "btn-primary");
        botonVerCuenta.addEventListener("click", () => {
          window.location.href = `./../estado-cuenta-cliente?nro_registro_maestro=${cheking.nro_registro_maestro}`;
        });
        tdCta.appendChild(botonVerCuenta);
      });

      // cargar los datos de los niños, adultos y pax
      const ninos = document.getElementById("ninos");
      const adultos = document.getElementById("adultos");
      const pax = document.getElementById("pax");

      ninos.innerText = chekings.reduce(
        (acumulador, cheking) =>
          acumulador + cheking.nro_ninos + cheking.nro_infantes,
        0
      );
      adultos.innerText = chekings.reduce(
        (acumulador, cheking) => acumulador + cheking.nro_adultos,
        0
      );
      pax.innerText = chekings.reduce(
        (acumulador, cheking) => acumulador + cheking.nro_personas,
        0
      );
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los chekings", "consultar");
    }
  }

  // devuelve un string con el siguiente formato: "3 (2,1)", que corresponde a 3 personas, 2 adultos y 1 niño (los niños también incluyen a los infantes). Si no hay niños, el string sería "3"
  function obtenerStringNroPersonas(cheking) {
    if (!cheking.nro_personas) {
      return "-";
    }

    const nroPersonas = cheking.nro_personas;
    const nroAdultos = cheking.nro_adultos;
    const nroNinosInfantes = cheking.nro_ninos + cheking.nro_infantes;

    if (nroNinosInfantes > 0) {
      return `${nroPersonas} (${nroAdultos},${nroNinosInfantes})`;
    } else {
      return `${nroPersonas}`;
    }
  }

  // devuelve el tiempo en horas y minutos entre la fecha y hora actual y la fecha y hora de entrada del cheking
  function obtenerTiempoTranscurrido(cheking, esRooming = false) {
    const fecha = esRooming ? cheking.fecha : cheking.fecha_in;
    const hora = esRooming ? cheking.hora.substr(0, 5) : cheking.hora_in;

    const fechaHoraIn = new Date(`${fecha} ${hora}`);
    const fechaHoraActual = new Date();

    let tiempoTranscurrido = fechaHoraActual - fechaHoraIn;

    if (tiempoTranscurrido > 1000 && tiempoTranscurrido < 1000 * 60) {
      return "Ahora";
    } else if (tiempoTranscurrido <= 1000) {
      return "Hace un momento";
    }

    const tiempoTranscurridoEnDias = tiempoTranscurrido / 1000 / 60 / 60 / 24;
    const tiempoTranscurridoEnHoras =
      (tiempoTranscurrido / 1000 / 60 / 60) % 24;
    const tiempoTranscurridoEnMinutos = (tiempoTranscurrido / 1000 / 60) % 60;

    // mostrar solo los días y horas si son mayores a 0
    return `${
      tiempoTranscurridoEnDias >= 1
        ? Math.floor(tiempoTranscurridoEnDias) + "d "
        : ""
    }${
      tiempoTranscurridoEnHoras >= 1
        ? Math.floor(tiempoTranscurridoEnHoras) + "h "
        : ""
    }${Math.floor(tiempoTranscurridoEnMinutos)}m`;
  }

  function actualizarFechaHora() {
    const fecha = document.getElementById("fecha");
    const hora = document.getElementById("hora");

    const fechaActual = new Date();

    fecha.innerText = fechaActual.toLocaleDateString();
    hora.innerText = fechaActual.toLocaleTimeString();
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-clientes").tBodies[0];
    tbody.innerHTML = "";
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
