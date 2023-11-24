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
      <h2 class="text-center">LIQUIDACIÓN DE SERVICIOS DE SPA</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3">
          Fecha:
          <input
            type="date"
            class="form-control"
            id="fecha"
            onchange="buscarServicios()"
          />
        </div>
        <div class="col-md-3">
          Especialista:
          <select
            class="form-select"
            id="id_profesional"
            onchange="buscarServicios()"
          ></select>
        </div>
      </div>

      <div class="table-responsive">
        <table id="tabla-servicios" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">Servicio</th>
              <th class="text-center">TIPO CL.</th>
              <th class="text-center">CLIENTE</th>
              <th class="text-center">Nro. Serv.</th>
              <th class="text-center">C. Servicio</th>
              <th class="text-center">P.COM. %</th>
              <th class="text-center">M. Comisión</th>
              <th class="text-center">ESTADO</th>
              <th class="text-center">RECIBO</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-md-2">
          <button
            class="btn btn-outline-secondary w-100"
            onclick="imprimirReporte()"
          >
            <i class="fas fa-print"></i> Imprimir reporte
          </button>
        </div>
        <div class="col-md-2">
          <button
            class="btn btn-outline-primary w-100"
            onclick="mostrarModalTotalizar()"
          >
            Totalizar
          </button>
        </div>
        <div class="col-md-auto d-flex align-items-center ms-auto">
          <label for="total">TOTAL:</label>
        </div>
        <div class="col-md-auto">
          <input
            type="text"
            class="form-control text-end"
            id="total"
            disabled
          />
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="modal-totalizar"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modal-totalizar-label"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-totalizar-label">Totalizar</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-recibo"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row w-100 mx-auto">
          <div class="col-md-12">
            <label for="terapista-totalizar">Terapista:</label>
            <input
              type="text"
              class="form-control"
              id="terapista-totalizar"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="fecha-totalizar">Fecha:</label>
            <input
              type="text"
              class="form-control"
              id="fecha-totalizar"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="monto-total-totalizar">Monto TOTAL:</label>
            <input
              type="text"
              class="form-control"
              id="monto-total-totalizar"
              disabled
            />
          </div>
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-primary w-100 mt-3"
              onclick="totalizar()"
            >
              Totalizar
            </button>
          </div>
          <div class="col-md-6">
            <button
              type="button"
              class="btn btn-secondary w-100 mt-3"
              data-bs-dismiss="modal"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiDocumentosDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-detalles";
  const apiTerapistasUrl = "<?php echo URL_API_NUEVA ?>/terapistas";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";
  const apiComrpobantesUrl = "<?php echo URL_API_NUEVA ?>/comprobantes-ventas";

  let modalTotalizar;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    modalTotalizar = new bootstrap.Modal(
      document.getElementById("modal-totalizar")
    );

    const params = new URLSearchParams(window.location.search);
    const fecha = params.get("fecha");
    if (fecha) {
      document.getElementById("fecha").value = fecha;
    } else {
      document.getElementById("fecha").valueAsDate = new Date();
    }

    await cargarTerapistas();
    buscarServicios();
  }

  async function cargarTerapistas() {
    try {
      const response = await fetch(apiTerapistasUrl);
      const terapistas = await response.json();

      const select = document.getElementById("id_profesional");

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.innerHTML = "Seleccione un terapista";
      select.appendChild(defaultOption);

      terapistas.forEach((terapista) => {
        const option = document.createElement("option");
        option.value = terapista.id_profesional;
        option.innerHTML = terapista.apellidos + ", " + terapista.nombres;
        select.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los terapistas", "consultar");
    }
  }

  async function buscarServicios() {
    const fecha = document.getElementById("fecha").value;
    const idProfesional = document.getElementById("id_profesional").value;

    if (!fecha || !idProfesional) {
      limpiarTabla();
      return;
    }

    const url = `${apiDocumentosDetallesUrl}?liquidacion&fecha=${fecha}&id_profesional=${idProfesional}`;

    await cargarServicios(url);
  }

  async function cargarServicios(url) {
    try {
      const response = await fetch(url);
      const servicios = await response.json();

      limpiarTabla();

      let totalMontoComision = 0;

      const tbody = document.getElementById("tabla-servicios").tBodies[0];
      servicios.forEach((servicio) => {
        const row = tbody.insertRow();

        row.dataset.id = servicio.id_documentos_detalle;

        const nombreServicio = row.insertCell();
        nombreServicio.innerHTML = servicio.servicio;

        const tipoCliente = row.insertCell();
        tipoCliente.innerHTML = servicio.tipo_cliente;

        const cliente = row.insertCell();
        cliente.innerHTML = servicio.cliente;

        const nroServicio = row.insertCell();
        nroServicio.innerHTML = servicio.nro_servicio;

        const costoServicio = row.insertCell();
        costoServicio.classList.add("text-end");
        costoServicio.innerHTML = formatearCantidad(servicio.costo_servicio);

        const porcComision = row.insertCell();
        porcComision.classList.add("text-end");
        porcComision.innerHTML = formatearCantidad(servicio.porc_comision);

        const montoComision = row.insertCell();
        montoComision.classList.add("text-end");
        montoComision.innerHTML = formatearCantidad(servicio.monto_comision);

        const estado = row.insertCell();
        estado.innerHTML = servicio.estado_servicio == 10 ? "LIQUIDADO" : "";

        const recibo = row.insertCell();
        recibo.innerHTML = servicio.recibo_liquidado;

        totalMontoComision += +servicio.monto_comision;
      });

      document.getElementById("total").value =
        formatearCantidad(totalMontoComision);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los comprobantes", "consultar");
    }
  }

  function imprimirReporte() {
    const fecha = document.getElementById("fecha").value;
    const idProfesional = document.getElementById("id_profesional").value;

    if (!fecha || !idProfesional) {
      return;
    }

    const url = `${apiReportesUrl}?tipo=liquidacion&fecha=${fecha}&id_profesional=${idProfesional}`;

    window.open(url, "_blank");
  }

  function mostrarModalTotalizar() {
    const terapistaTotalizar = document.getElementById("terapista-totalizar");
    const fechaTotalizar = document.getElementById("fecha-totalizar");
    const montoTotalTotalizar = document.getElementById(
      "monto-total-totalizar"
    );

    const idProfesional =
      document.getElementById("id_profesional").selectedOptions[0].text;
    const fechaSeleccionada = document.getElementById("fecha").value;
    const total = document.getElementById("total").value;

    if (!idProfesional || !fechaSeleccionada) {
      return;
    }

    terapistaTotalizar.value = idProfesional;
    fechaTotalizar.value = fechaSeleccionada;
    montoTotalTotalizar.value = total;

    modalTotalizar.show();
  }

  async function totalizar() {
    const idUsuario = "<?php echo $_SESSION['usuario']['id_usuario']; ?>";
    const idProfesional = document.getElementById("id_profesional").value;
    const fecha = document.getElementById("fecha").value;

    if (!idProfesional || !fecha) {
      return;
    }

    const url = `${apiComrpobantesUrl}/liquidacion-servicios`;
    const options = {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_usuario: idUsuario,
        id_profesional: idProfesional,
        fecha: fecha,
      }),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);

      if (data.error) {
        mostrarAlert("error", data.error, "crear");
        return;
      }

      mostrarAlert("ok", "Se totalizó correctamente", "crear");
      modalTotalizar.hide();
      buscarServicios();

      const nroComprobante = data.resultado.comprobante.nro_comprobante;

      const comprobanteUrl = `${apiReportesUrl}?tipo=generar-factura&nro_comprobante=${nroComprobante}`;

      open(comprobanteUrl, "_blank");

    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo totalizar", "crear");
    }
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-servicios").tBodies[0];
    tbody.innerHTML = "";
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
