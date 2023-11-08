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
      <h2 class="text-center">PROGRAMACIÓN DE SERVICIOS DE SPA</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3">
          Fecha: <input type="date" class="form-control" id="fecha"
          onchange="buscarServicios()" value="<?php echo date("Y-m-d"); ?>"/>
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
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-cambiar-estado"
  tabindex="-1"
  role="dialog"
  aria-labelledby="modal-cambiar-estado-label"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-cambiar-estado-label">
          Cambiar estado
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-recibo"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row w-75 mx-auto">
          <div class="col-md-12">
            <label for="nro-voucher">Cliente:</label>
            <input type="text" class="form-control" id="cliente" disabled />
          </div>
          <div class="col-md-12">
            <label for="nro-voucher">Nro Comprobante:</label>
            <input
              type="text"
              class="form-control"
              id="nro-comprobante"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="nro-voucher">Fecha:</label>
            <input
              type="text"
              class="form-control"
              id="fecha-confirmar"
              disabled
            />
          </div>
          <div class="col-md-12">
            <label for="nro-voucher">Monto TOTAL:</label>
            <input type="text" class="form-control" id="monto-total" disabled />
          </div>
        </div>

        <form id="form-cambiar-estado">
          <div class="row w-75 mx-auto p-3 border border-1 rounded-2 mt-3">
            <div class="col-md-12 mb-3">
              <label for="nro-voucher">Ingrese Usuario:</label>
              <input type="text" class="form-control" id="usuario" required />
            </div>
            <div class="col-md-12 mb-3">
              <label for="nro-voucher">Ingrese contraseña:</label>
              <input
                type="password"
                class="form-control"
                id="contraseña"
                required
              />
            </div>
            <input
              type="submit"
              class="btn btn-danger me-auto mb-3"
              id="anular-comprobante"
              value="ANULAR"
            />
            <button
              class="btn btn-outline-secondary mb-3"
              data-bs-dismiss="modal"
            >
              SALIR
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const apiDocumentosDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-detalles";
  const apiTerapistasUrl = "<?php echo URL_API_NUEVA ?>/terapistas";

  let modal;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    modal = new bootstrap.Modal(
      document.getElementById("modal-cambiar-estado")
    );

    await cargarTerapistas();
    buscarServicios();
    prepararFormulario();
  }

  function prepararFormulario() {
    const formAnularComprobante = document.getElementById(
      "form-cambiar-estado"
    );

    formAnularComprobante.addEventListener("submit", (event) => {
      event.preventDefault();
      anularComprobante();
    });
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
        estado.innerHTML = servicio.estado;
        
        const recibo = row.insertCell();
        recibo.innerHTML = servicio.recibo;
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los comprobantes", "consultar");
    }
  }

  function mostrarModalCambiarEstado(event) {
    const row = event.target.closest("tr");

    const cliente = document.getElementById("cliente");
    const nroComprobante = document.getElementById("nro-comprobante");
    const fecha = document.getElementById("fecha-confirmar");
    const montoTotal = document.getElementById("monto-total");
    const form = document.getElementById("form-cambiar-estado");

    cliente.value = row.dataset.nombre;
    nroComprobante.value = row.dataset.nro_comprobante;
    fecha.value = row.dataset.fecha;
    montoTotal.value = row.dataset.monto;
    form.dataset.id = row.dataset.id;

    modal.show();
  }

  async function anularComprobante() {
    const id = document.getElementById("form-cambiar-estado").dataset.id;
    const usuario = document.getElementById("usuario").value;
    const clave = document.getElementById("contraseña").value;

    // limpiar formulario
    document.getElementById("usuario").value = "";
    document.getElementById("contraseña").value = "";

    const url = `${apiDocumentosDetallesUrl}/${id}/anular`;

    const body = {
      usuario,
      clave,
    };

    const options = {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      alert(data.mensaje);
      buscarServicios();

      modal.hide();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo anular el comprobante", "borrar");
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
