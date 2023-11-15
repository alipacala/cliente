<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); $checkingId =
$_GET["nro_registro_maestro"] ?? ""; $idUsuario =
$_SESSION["usuario"]["id_usuario"]; ?>

<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">Estado de cuenta del Cliente</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="form-group col-md-6">
          <label for="nombre-cliente">Nombre del Cliente</label>
          <input
            type="text"
            class="form-control"
            id="nombre-cliente"
            name="nombre-cliente"
            disabled
          />
        </div>
        <div class="form-group col-md-2">
          <label for="tipo-checkin">Tipo</label>
          <input
            type="text"
            class="form-control"
            id="tipo-checkin"
            name="tipo"
            disabled
          />
        </div>
        <div class="form-group col-md-2">
          <label for="nro_registro_maestro">Nro. Registro Maestro</label>
          <input
            type="text"
            class="form-control"
            id="nro_registro_maestro"
            name="nro_registro_maestro"
            disabled
          />
        </div>
        <div class="form-group col-md-2">
          <label for="nro_habitacion">Nro. Habitación</label>
          <input
            type="text"
            class="form-control"
            id="nro_habitacion"
            name="nro_habitacion"
            disabled
          />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12 text-end">
          <a
            href="./../agregar-comanda?nro_registro_maestro=<?php echo $checkingId; ?>"
            class="btn btn-success"
          >
            <i class="fas fa-plus"></i>
            Agregar comanda
          </a>
          <button class="btn btn-danger" id="anular-items">
            <i class="fas fa-minus"></i>
            Anular items
          </button>
          <button class="btn btn-warning" id="cerrar-cuenta" disabled>
            Cerrar cuenta
          </button>
        </div>
      </div>

      <div class="table-responsive mb-4">
        <table class="table table-bordered table-hover" id="tabla-comandas">
          <thead>
            <tr>
              <th style="min-width: 100px">Nro. Hab.</th>
              <th style="min-width: 120px">Fecha</th>
              <th
                id="nombre-producto"
                data-page="estado-cuenta-cliente"
                style="min-width: 360px"
              >
                Producto o servicio
              </th>
              <th>Cant.</th>
              <th style="min-width: 140px">P. Unit.</th>
              <th style="min-width: 140px">P. Total</th>
              <th style="min-width: 100px">
                <a href="#" id="seleccionar">Des. Pago</a>
              </th>
              <th style="max-width: 100px">Cliente</th>
              <th style="max-width: 100px">Terapeuta</th>
              <th style="min-width: 120px">Ingreso</th>
              <th style="min-width: 100px">Salida</th>
              <th>Observaciones</th>
              <th style="min-width: 160px">Doc. Pago</th>
              <th style="min-width: 120px">Fecha Pago</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div class="row mb-4">
        <div class="col">
          <div class="d-inline-flex flex-column">
            <input
              type="button"
              class="btn btn-outline-secondary"
              id="ver-acompanantes"
              value="Ver Acompañantes"
            />
            <span
              class="btn-link"
              style="cursor: pointer"
              onclick="mostrarModalAgregarAcompanante()"
              >Agregar acompañante</span
            >
          </div>
          <input
            type="button"
            class="btn btn-outline-secondary"
            id="ver-reporte"
            value="Ver reporte"
          />
          <input
            type="button"
            class="btn btn-primary btn-lg float-end"
            id="totalizar"
            value="Totalizar"
          />
        </div>
      </div>

      <h4>Relación de comprobantes emitidos</h4>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabla-comprobantes">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>NRO.DOC.</th>
              <th>RUC</th>
              <th>NOMBRE/RAZON SOCIAL</th>
              <th>MONTO TOTAL</th>
              <th>F.PAGO</th>
              <th>TIPO PAGO</th>
              <th>MONTO</th>
              <th>GENERAR RECIBO</th>
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
  id="modal-comprobante"
  tabindex="-1"
  aria-labelledby="modal-comprobante-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-comprobante-label">Totalizar</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-comprobante"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-comprobante">
          <div class="row">
            <div class="col-md-4">
              <label for="fecha">Fecha:</label>
              <input
                type="date"
                class="form-control fecha"
                id="fecha"
                disabled
              />
            </div>
            <div class="col-md-4">
              <label for="hora">Hora:</label>
              <input type="time" class="form-control hora" id="hora" disabled />
            </div>
            <div class="col-md-4">
              <label for="monto-total" class="fw-bold fs-5">Monto Total:</label>
              <input
                type="text"
                class="form-control monto-total fw-bold fs-5 text-center"
                id="monto-total"
                disabled
              />
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label for="tipo">Tipo de Comprobante:</label>
              <select class="form-select tipo" id="tipo">
                <option value="00">PEDIDO</option>
                <option value="01">FACTURA</option>
                <option value="03">BOLETA</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="tipo-documento">Tipo de Documento Identidad:</label>
              <select class="form-select tipo-documento" id="tipo-documento">
                <option value="0">Sin Documento</option>
                <option value="1">DNI</option>
                <option value="6">RUC</option>
                <option value="7">Pasaporte</option>
              </select>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label for="nro-documento">Nro Documento:</label>
              <div class="input-group">
                <input
                  type="text"
                  class="form-control nro-documento"
                  id="nro-documento"
                />
                <div class="input-group-text">
                  <span
                    class="spinner-border spinner-border-sm invisible"
                    id="spinner"
                    role="status"
                  ></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <label for="nombre">Nombre:</label>
              <input type="text" class="form-control nombre" id="nombre" />
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label for="direccion">Dirección:</label>
              <input
                type="text"
                class="form-control direccion"
                id="direccion"
              />
            </div>
            <div class="col-md-6">
              <label for="lugar">Lugar:</label>
              <input type="text" class="form-control lugar" id="lugar" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="submit"
            class="btn btn-primary col-md-6"
            id="crear-comprobante"
          >
            Aceptar
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            data-bs-dismiss="modal"
          >
            Salir
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal-lg fade"
  id="modal-recibo"
  tabindex="-1"
  aria-labelledby="modal-recibo-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-recibo-label">Generar Recibo</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-recibo"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-recibo">
          <div class="row">
            <div class="col-md-4 ms-auto">
              <label for="total" class="fw-bold fs-5">TOTAL:</label>
              <input
                type="number"
                class="form-control fw-bold fs-5"
                id="total"
                disabled
              />
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label for="tipo-doc">Tipo de Comprobante:</label>
              <input type="text" class="form-control" id="tipo-doc" disabled />
            </div>
            <div class="col-md-4">
              <label for="nro-comprobante">Nro. de Comprobante:</label>
              <input
                type="text"
                class="form-control"
                id="nro-comprobante"
                disabled
              />
            </div>
            <div class="col-md-4">
              <label for="cliente">Cliente:</label>
              <input type="text" class="form-control" id="cliente" disabled />
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label for="medio-pago">Medio de Pago:</label>
              <select class="form-select" id="medio-pago">
                <option value="EFE">Efectivo</option>
                <option value="YAP">Yape</option>
                <option value="PLI">Plin</option>
                <option value="TJT">Tarjeta</option>
                <option value="DEP">Depósito</option>
                <option value="TRA">Transferencia</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="nro-voucher">Nro. de Voucher:</label>
              <input
                type="text"
                class="form-control"
                id="nro-voucher"
                disabled
              />
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label for="monto">Monto:</label>
              <input type="number" class="form-control" id="monto" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="col-md-6">
          <div class="d-flex justify-content-end">
            <button
              type="button"
              class="btn btn-primary me-2"
              id="crear-recibo"
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

<div
  class="modal fade"
  id="modal-cerrar-cuenta"
  tabindex="-1"
  aria-labelledby="modal-cerrar-cuenta-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="modal-cerrar-cuenta-label">
          ¿Está seguro que desea cerrar la cuenta?
        </h5>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-primary col-md-6"
            onclick="cerrarCuenta()"
            data-bs-dismiss="modal"
          >
            Sí
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            id="confirmar-cerrar-cuenta"
            data-bs-dismiss="modal"
          >
            No
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-ver-reporte"
  tabindex="-1"
  aria-labelledby="modal-ver-reporte-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-ver-reporte-label">
          Reporte Estado de cuenta del cliente
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-ver-reporte"
        ></button>
      </div>
      <div class="modal-body">
        <p>Seleccione una opción</p>
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-primary col-md-6"
            id="ver-reporte-todos"
            data-bs-dismiss="modal"
          >
            Ver todos los consumos
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            id="ver-reporte-por-cobrar"
            data-bs-dismiss="modal"
          >
            Ver sólo consumos por cobrar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-acompanantes"
  tabindex="-1"
  aria-labelledby="modal-acompanantes-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-acompanantes-label">
          Relación de acompañantes
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-acompanantes"
        ></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table
            class="table table-bordered table-hover"
            id="tabla-acompanantes"
          >
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Parentesco</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal"
        >
          Salir
        </button>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade modal-lg"
  id="modal-ver-comprobante"
  tabindex="-1"
  aria-labelledby="modal-ver-comprobante-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-ver-comprobante-label">
          Comprobante
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-ver-comprobante"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-3">
            <label for="ver-fecha-comprobante">Fecha:</label>
            <input
              type="text"
              class="form-control"
              id="ver-fecha-comprobante"
              disabled
            />
          </div>
          <div class="col-md-3">
            <label for="ver-nro-comprobante">Nro comprobante:</label>
            <input
              type="text"
              class="form-control"
              id="ver-nro-comprobante"
              disabled
            />
          </div>
          <div class="col-md-3">
            <label for="ver-doc-cliente">DNI/RUC:</label>
            <input
              type="text"
              class="form-control"
              id="ver-doc-cliente"
              disabled
            />
          </div>
          <div class="col-md-3">
            <label for="ver-nombre-razon-social">Nombre/Razón Social:</label>
            <input
              type="text"
              class="form-control"
              id="ver-nombre-razon-social"
              disabled
            />
          </div>
        </div>

        <div class="table-responsive">
          <table
            class="table table-bordered table-hover"
            id="tabla-ver-comprobante"
          >
            <thead>
              <tr>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal"
        >
          Salir
        </button>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade"
  id="modal-anular-items"
  tabindex="-1"
  aria-labelledby="modal-anular-items-label"
  style="display: none"
  aria-hidden="true"
>
  <div
    class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
  >
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="modal-anular-items-label">
          ¿Realmente desea anular los items?
        </h5>
        <p>Ha seleccionado los siguientes items:</p>
        <div class="table-responsive">
          <table
            class="table table-bordered table-hover"
            id="tabla-anular-items"
          >
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Precio Total</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-danger col-md-6"
            id="confirmar-anular-items"
            data-bs-dismiss="modal"
          >
            Anular items
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            data-bs-dismiss="modal"
          >
            Salir
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PARA CREAR ACOMPANANTE CON apellidos, nombres; edad; sexo -->
<div
  class="modal fade"
  id="modal-acompanante"
  tabindex="-1"
  aria-labelledby="modal-acompanante-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-acompanante-label">
          Agregar acompañante
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-acompanante"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <label for="acompanante">Apellidos y Nombres:</label>
            <input
              type="text"
              class="form-control"
              id="acompanante"
              name="acompanante"
            />
          </div>
          <div class="col-md-12">
            <label for="edad">Edad:</label>
            <input type="number" class="form-control" id="edad" name="edad" />
          </div>
          <div class="col-md-12">
            <label for="sexo">Sexo:</label>
            <select class="form-select" id="sexo" name="sexo">
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="submit"
            class="btn btn-primary col-md-6"
            id="crear-acompanante"
            onclick="crearAcompanante()"
          >
            Aceptar
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            data-bs-dismiss="modal"
          >
            Salir
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const configId = 4;
  const apiConfigUrl = `<?php echo URL_API_NUEVA ?>/config/${configId}/codigo`;
  const apiCheckingsUrl = "<?php echo URL_API_NUEVA ?>/checkings";
  const apiAcompanantesUrl = "<?php echo URL_API_NUEVA ?>/acompanantes";
  const apiDocumentosDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-detalles";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiTerapistasUrl = "<?php echo URL_API_NUEVA ?>/terapistas";
  const apiRoomingUrl = "<?php echo URL_API_NUEVA ?>/rooming";
  const apiComprobantesUrl = "<?php echo URL_API_NUEVA ?>/comprobantes-ventas";
  const apiRecibosUrl = "<?php echo URL_API_NUEVA ?>/recibos-pago";
  const apiSunatUrl = "<?php echo URL_API_NUEVA ?>/sunat";
  const apiPersonasUrl = "<?php echo URL_API_NUEVA ?>/personas";
  const apiComprobantesDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/comprobantes-detalles";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";

  let checking = null;
  let persona = null;

  let productos = [];
  let acompanantes = [];
  let terapistas = [];
  let filasSeleccionables = [];

  const tiposComprobante = {
    "00": "PEDIDO",
    "01": "FACTURA",
    "03": "BOLETA",
  };

  const formasPago = {
    EFE: "Efectivo",
    YAP: "Yape",
    PLI: "Plin",
    TJT: "Tarjeta",
    DEP: "Depósito",
    TRA: "Transferencia",
  };

  let modalComprobante;
  let modalRecibo;
  let modalConfirmar;
  let modalAcompanantes;
  let modalVerComprobante;
  let modalAnularItems;
  let modalAgregarAcompanante;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarDatosChecking();
    await cargarDatosProductos();
    await cargarDatosAcompanantes();
    await cargarDatosTerapistas();
    await cargarDatosDocumentosDetalles();
    await cargarDatosComprobantes();
    await cargarDatosPersona();

    const modalOptions = {
      backdrop: "static",
      keyboard: false,
    };

    modalComprobante = new bootstrap.Modal(
      document.getElementById("modal-comprobante"),
      modalOptions
    );

    modalRecibo = new bootstrap.Modal(
      document.getElementById("modal-recibo"),
      modalOptions
    );

    modalConfirmar = new bootstrap.Modal(
      document.getElementById("modal-cerrar-cuenta"),
      modalOptions
    );

    modalAcompanantes = new bootstrap.Modal(
      document.getElementById("modal-acompanantes"),
      modalOptions
    );

    modalVerComprobante = new bootstrap.Modal(
      document.getElementById("modal-ver-comprobante"),
      modalOptions
    );

    modalVerReporte = new bootstrap.Modal(
      document.getElementById("modal-ver-reporte"),
      modalOptions
    );

    modalAnularItems = new bootstrap.Modal(
      document.getElementById("modal-anular-items"),
      modalOptions
    );

    modalAgregarAcompanante = new bootstrap.Modal(
      document.getElementById("modal-acompanante"),
      modalOptions
    );

    prepararSeleccionar();
    prepararCrearComprobante();
    prepararTotalizar();
    prepararCrearRecibo();
    prepararSelectTipoComprobante();
    prepararSelectTipoDocumento();
    prepararSelectMedioPago();
    alCambiarNroDoc();
    alCerrarCuenta();
    actualizarBotonCerrarCuenta();

    prepararVerAcompanantes();
    prepararVerReporte();
    prepararAnularItems();
    prepararConfirmarAnularItems();

    cargarAcompanantesEnTabla();

    const tooltipTriggerList = document.querySelectorAll(
      '[data-bs-toggle="tooltip"]'
    );
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );

    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
  }

  function prepararConfirmarAnularItems() {
    const confirmarAnularItems = document.getElementById(
      "confirmar-anular-items"
    );

    confirmarAnularItems.addEventListener("click", async () => {
      const filasSeleccionadas = filasSeleccionables.filter(
        (row) => row.querySelector("input").checked
      );

      const filasSeleccionadasNoSVH = filasSeleccionadas.filter(
        (row) => row.dataset.tipo_producto != "SVH"
      );

      const ids = filasSeleccionadasNoSVH.map((row) => row.dataset.id);

      const options = {
        method: "DELETE",
      };

      let seAnularon = true;
      ids.forEach(async (id) => {
        const url = `${apiDocumentosDetallesUrl}/${id}/anular`;

        try {
          const response = await fetch(url, options);
          const data = await response.json();

          if (!response.ok) {
            seAnularon = false;
            console.error(data);
          }
        } catch (error) {
          seAnularon = false;
          console.error(error);
        }

        if (seAnularon) {
          mostrarAlert("ok", "Se anularon los items seleccionados", "borrar");
          await cargarDatosDocumentosDetalles();
        } else {
          mostrarAlert("error", "No se pudo anular los items", "borrar");
        }
      });
    });
  }

  function prepararAnularItems() {
    const anularItems = document.getElementById("anular-items");
    anularItems.addEventListener("click", () => {
      const filasSeleccionadas = filasSeleccionables.filter(
        (row) => row.querySelector("input").checked
      );

      const filasSeleccionadasNoSVH = filasSeleccionadas.filter(
        (row) => row.dataset.tipo_producto != "SVH"
      );

      if (filasSeleccionadasNoSVH.length == 0) {
        mostrarAlert(
          "error",
          "Debe seleccionar al menos un item (no se pueden seleccionar habitaciones para anular)",
          "borrar"
        );
        return;
      }

      // obtener el nombre de los productos seleccionados
      const detallesSeleccionados = filasSeleccionadasNoSVH.map((fila) => ({
        nombre: fila.dataset.nombreProducto,
        cantidad: fila.dataset.cantidad,
        precio: formatearCantidad(fila.dataset.precioUnitario),
        total: formatearCantidad(fila.dataset.total),
      }));

      // cargar los datos en la tabla de confirmación
      const tablaAnularItems = document.getElementById("tabla-anular-items");
      const tbody = tablaAnularItems.querySelector("tbody");
      tbody.innerHTML = "";

      detallesSeleccionados.forEach((detalle) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${detalle.nombre}</td>
          <td class="text-center">${detalle.cantidad}</td>
          <td class="text-end">${detalle.precio}</td>
          <td class="text-end">${detalle.total}</td>
        `;

        tbody.appendChild(tr);
      });

      // mostrar el modal de confirmación
      modalAnularItems.show();
    });
  }

  function prepararVerReporte() {
    const verReporte = document.getElementById("ver-reporte");
    verReporte.addEventListener("click", (event) => {
      event.preventDefault();

      modalVerReporte.show();
    });

    const verReporteTodos = document.getElementById("ver-reporte-todos");
    verReporteTodos.addEventListener("click", (event) => {
      event.preventDefault();
      const url = `${apiReportesUrl}?tipo=estado-cuenta-cliente&consumos=todos&nro_registro_maestro=${checking.nro_registro_maestro}`;
      open(url, "_blank");
    });

    const verReportePorCobrar = document.getElementById(
      "ver-reporte-por-cobrar"
    );
    verReportePorCobrar.addEventListener("click", (event) => {
      event.preventDefault();
      const url = `${apiReportesUrl}?tipo=estado-cuenta-cliente&consumos=por-cobrar&nro_registro_maestro=${checking.nro_registro_maestro}`;
      open(url, "_blank");
    });
  }

  function prepararVerAcompanantes() {
    const verAcompanantes = document.getElementById("ver-acompanantes");
    verAcompanantes.addEventListener("click", (event) => {
      event.preventDefault();

      modalAcompanantes.show();
    });
  }

  function prepararSelectMedioPago() {
    const medioPago = document.getElementById("medio-pago");
    medioPago.addEventListener("change", (event) => {
      const nroComprobante = document.getElementById("nro-voucher");
      if (event.target.value == "EFE") {
        nroComprobante.disabled = true;
        nroComprobante.value = "";
      } else {
        nroComprobante.disabled = false;
      }
    });
  }

  function prepararSelectTipoComprobante() {
    const tipoComprobante = document.getElementById("tipo");
    tipoComprobante.addEventListener("change", (event) => {
      const tipoDocumento = document.getElementById("tipo-documento");

      if (event.target.value == "01") {
        tipoDocumento.innerHTML = `
           <option value="6" selected>RUC</option>
         `;
      } else if (event.target.value == "03") {
        tipoDocumento.innerHTML = `
           <option value="1" selected>DNI</option>
           <option value="6">RUC</option>
         `;
      } else {
        tipoDocumento.innerHTML = `
           <option value="0" selected>Sin Documento</option>
           <option value="1">DNI</option>
           <option value="6">RUC</option>
           <option value="7">Pasaporte</option>
         `;
      }

      alCambiarTipoDoc(tipoDocumento);
    });
  }

  function prepararSelectTipoDocumento() {
    // si se selecciona Sin documento, deshabilitar el campo de nro de documento
    const tipoDocumento = document.getElementById("tipo-documento");
    tipoDocumento.addEventListener("change", (event) =>
      alCambiarTipoDoc(event.target)
    );
  }

  function alCambiarTipoDoc(element) {
    const nroDocumento = document.getElementById("nro-documento");
    const opcionSeleccionada = element.querySelector(
      `option[value="${element.value}"]`
    ).textContent;

    if (opcionSeleccionada == "Sin Documento") {
      nroDocumento.value = "";
      nroDocumento.disabled = true;
    }
  }

  function prepararCrearRecibo() {
    const crearRecibo = document.getElementById("crear-recibo");
    crearRecibo.addEventListener("click", async (event) => {
      event.preventDefault();

      const tipoDoc = document.getElementById("tipo-doc").value;
      const nroComprobante = document.getElementById("nro-comprobante").value;
      const cliente = document.getElementById("cliente").value;
      const total = document.getElementById("total").value;
      const medioPago = document.getElementById("medio-pago").value;
      const nroVoucher = document.getElementById("nro-voucher").value;
      const monto = document.getElementById("monto").value;

      const params = new URLSearchParams(window.location.search);
      const nroRegistroMaestro = params.get("nro_registro_maestro");

      const recibo = {
        id_comprobante_ventas: idComprobanteVentas,
        medio_pago: medioPago,
        nro_voucher: nroVoucher,
        total: monto,
        id_usuario: "<?php echo $idUsuario; ?>",
      };

      const options = {
        method: "POST",
        body: JSON.stringify(recibo),
        headers: {
          "Content-Type": "application/json",
        },
      };

      try {
        const response = await fetch(apiRecibosUrl, options);
        const data = await response.json();

        document.getElementById("medio-pago").value = "EFE";
        document.getElementById("nro-voucher").value = "";

        modalRecibo.hide();

        cargarDatosComprobantes();
        cargarDatosDocumentosDetalles();
        actualizarBotonCerrarCuenta();
      } catch (error) {
        console.error(error);
        mostrarAlert("error", "Error al crear el recibo", "crear");
      }
    });
  }

  function actualizarFilasSeleccionables() {
    filasSeleccionables = [...document.querySelectorAll("tbody tr")];
    filasSeleccionables = filasSeleccionables.filter(
      (row) => row.querySelector("input") != null
    );
  }

  async function prepararTotalizar() {
    const totalizar = document.getElementById("totalizar");
    totalizar.addEventListener("click", async (event) => {
      event.preventDefault();

      const filasSeleccionadas = filasSeleccionables.filter(
        (row) => row.querySelector("input").checked
      );

      if (filasSeleccionadas.length == 0) {
        alert("Debe seleccionar al menos una comanda");
        return;
      }

      mostrarModalComprobante();
    });
  }

  async function cargarDatosComprobantes() {
    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");

    const url = `${apiComprobantesUrl}?nro_registro_maestro=${nroRegistroMaestro}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      let comprobantes = data;

      const tbody = document.getElementById("tabla-comprobantes").tBodies[0];
      tbody.innerHTML = "";

      // filtrar por estado = 1
      comprobantes = comprobantes.filter((comprobante) => comprobante.estado);

      // agrupar los comprobantes por nro de comprobante y hacer un array con tipo de pago y total de recibo en cada componente
      const comprobantesAgrupados = [];
      comprobantes.forEach((comprobante) => {
        const comprobanteAgrupado = comprobantesAgrupados.find(
          (comprobanteAgrupado) =>
            comprobanteAgrupado.nro_comprobante == comprobante.nro_comprobante
        );

        // crea un array de recibos por cada comprobante con los campos tipo_pago y total_recibo
        if (comprobanteAgrupado) {
          comprobanteAgrupado.recibos.push({
            tipo_pago: comprobante.tipo_pago,
            total_recibo: comprobante.total_recibo,
          });
        } else {
          comprobante = {
            ...comprobante,
            recibos: [
              {
                tipo_pago: comprobante.tipo_pago,
                total_recibo: comprobante.total_recibo,
              },
            ],
          };
          comprobantesAgrupados.push(comprobante);
        }
      });

      comprobantesAgrupados.forEach((comprobante) => {
        const row = tbody.insertRow();

        row.role = "button";

        row.dataset.id = comprobante.id_comprobante_ventas;
        row.dataset.fechaComprobante = comprobante.fecha_comprobante;
        row.dataset.tipoComprobante = comprobante.tipo_comprobante;
        row.dataset.nroComprobante = comprobante.nro_comprobante;
        row.dataset.docCliente = comprobante.nro_doc_cliente;
        row.dataset.cliente = comprobante.nombre_razon_social;
        row.dataset.porPagar = comprobante.por_pagar;
        row.dataset.total = comprobante.total_comprobante;

        row.innerHTML = `
           <td>${formatearFecha(comprobante.fecha_comprobante)}</td>
           <td>${comprobante.nro_comprobante}</td>
           <td>${comprobante.nro_doc_cliente}</td>
           <td>${comprobante.nombre_razon_social}</td>
           <td class="text-end">${comprobante.total_comprobante}</td>
           <td>${tiposComprobante[comprobante.tipo_comprobante]}</td>
           <td></td>
           <td></td>
           <td>
             ${
               comprobante.por_pagar > 0
                 ? `<button class="btn btn-outline-success" onclick="mostrarModalRecibo(event)">Cobranza</button>`
                 : ""
             }
           </td>
         `;

        // al hacer click en la fila se muestra el modal comprobante
        row.addEventListener("click", (event) => {
          event.preventDefault();
          mostrarModalVerComprobante(row.dataset);
        });

        // insertar los valores en las celdas de tipo de pago y total de recibo
        comprobante.recibos.forEach((recibo, index) => {
          if (index == 0) {
            const tipoPagoCell = row.cells[6];
            const totalReciboCell = row.cells[7];

            tipoPagoCell.innerHTML = formasPago[recibo.tipo_pago] ?? "";
            totalReciboCell.innerHTML = recibo.total_recibo;
            totalReciboCell.classList.add("text-end");
          } else {
            const nuevaFila = tbody.insertRow();
            nuevaFila.innerHTML = `
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td>${formasPago[recibo.tipo_pago] ?? ""}</td>
               <td class="text-end">${recibo.total_recibo}</td>
               <td></td>
             `;
          }
        });
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos de los comprobantes",
        "consultar"
      );
    }
  }

  let idComprobanteVentas;

  async function mostrarModalVerComprobante(data) {
    const idComprobante = data.id;
    const total = data.total;

    const verFechaComprobante = document.getElementById(
      "ver-fecha-comprobante"
    );
    const verNroComprobante = document.getElementById("ver-nro-comprobante");
    const verDocCliente = document.getElementById("ver-doc-cliente");
    const verNombreRazonSocial = document.getElementById(
      "ver-nombre-razon-social"
    );

    verFechaComprobante.value = data.fechaComprobante;
    verNroComprobante.value = data.nroComprobante;
    verDocCliente.value = data.docCliente;
    verNombreRazonSocial.value = data.cliente;

    const url = `${apiComprobantesDetallesUrl}?comprobante=${idComprobante}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      const detalles = data;

      const tbody = document.getElementById("tabla-ver-comprobante").tBodies[0];
      tbody.innerHTML = "";

      detalles.forEach((documentoDetalle) => {
        const row = tbody.insertRow();

        const producto = productos.find(
          (producto) => producto.id_producto == documentoDetalle.id_producto
        );

        row.innerHTML = `
           <td>${documentoDetalle.cantidad}</td>
           <td>${producto.nombre_producto} ${
          producto.tipo == "SRV" ? `(${documentoDetalle.fecha_servicio})` : ""
        }</td>
           <td>${documentoDetalle.precio_unitario}</td>
           <td>${documentoDetalle.precio_total}</td>
         `;
      });

      tbody.insertRow().innerHTML = `
         <td colspan="3" class="fw-bold text-end">TOTAL</td>
         <td class="fw-bold">${total}</td>
       `;

      modalVerComprobante.show();
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos del comprobante",
        "consultar"
      );
    }
  }

  function mostrarModalRecibo(event) {
    event.stopPropagation();
    const row = event.target.closest("tr");

    const tipoComprobante = document.getElementById("tipo-doc");
    const nroComprobante = document.getElementById("nro-comprobante");
    const cliente = document.getElementById("cliente");
    const total = document.getElementById("total");
    const monto = document.getElementById("monto");

    tipoComprobante.value = tiposComprobante[row.dataset.tipoComprobante];
    nroComprobante.value = row.dataset.nroComprobante;
    cliente.value = row.dataset.cliente;
    total.value = row.dataset.porPagar;
    idComprobanteVentas = row.dataset.id;
    monto.value = row.dataset.porPagar;

    modalRecibo.show();
  }

  function prepararSeleccionar() {
    const seleccionar = document.getElementById("seleccionar");
    seleccionar.addEventListener("click", (event) => {
      event.preventDefault();

      filasSeleccionables.forEach((row) => {
        row.querySelector("input").checked = seleccionarTodos;
      });

      seleccionarTodos = !seleccionarTodos;
      seleccionar.textContent = seleccionarTodos ? "Sel. Pago" : "Des. Pago";

      const totalCell = document.getElementById("total-cantidad");
      totalCell.innerHTML = formatearCantidad(calcularTotal());
    });
  }

  function calcularTotal() {
    actualizarFilasSeleccionables();

    const filasSeleccionadas = filasSeleccionables.filter(
      (row) => row.querySelector("input").checked
    );

    let total = 0;
    filasSeleccionadas.forEach((row) => {
      total += parseFloat(row.dataset.total);
    });

    return total;
  }

  async function cargarDatosTerapistas() {
    try {
      const response = await fetch(apiTerapistasUrl);
      const data = await response.json();
      terapistas = data;
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos de los terapistas",
        "consultar"
      );
    }
  }

  async function cargarDatosAcompanantes() {
    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");

    const url = `${apiAcompanantesUrl}?nro_registro_maestro=${nroRegistroMaestro}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      acompanantes = data;
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos de los acompañantes",
        "consultar"
      );
    }
  }

  function cargarAcompanantesEnTabla() {
    const tbody = document.getElementById("tabla-acompanantes").tBodies[0];
    tbody.innerHTML = "";

    acompanantes.forEach((acompanante) => {
      const row = tbody.insertRow();

      row.innerHTML = `
         <td>${acompanante.apellidos_y_nombres}</td>
         <td>${acompanante.sexo}</td>
         <td>${acompanante.edad}</td>
         <td>${
           acompanante.nro_de_orden_unico > 0
             ? acompanante.parentesco ?? ""
             : "<span class='fw-bold'>TITULAR</span>"
         }</td>
       `;
    });
  }

  async function cargarDatosProductos() {
    try {
      const response = await fetch(apiProductosUrl);
      const data = await response.json();
      productos = data;
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos de los productos",
        "consultar"
      );
    }
  }

  async function cargarDatosDocumentosDetalles() {
    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");

    const url = `${apiDocumentosDetallesUrl}?nro_registro_maestro=${nroRegistroMaestro}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      let documentosDetalles = data;

      // filtrar los documentos detalles que tengan el nivel_descargo igual a 1
      documentosDetallesD1 = documentosDetalles.filter(
        (documentoDetalle) => documentoDetalle.id_item == 0
      );

      // filtrar los documentos detalles que tengan anulado = 1
      documentosDetallesD1 = documentosDetallesD1.filter(
        (documentoDetalle) => !documentoDetalle.anulado
      );

      // ordenar los documentos detalles por nro_habitacion y luego por fecha_hora_registro
      documentosDetallesD1.sort((a, b) => {
        if (a.nro_habitacion < b.nro_habitacion) {
          return -1;
        }
        if (a.nro_habitacion > b.nro_habitacion) {
          return 1;
        }
        if (a.nro_habitacion == b.nro_habitacion) {
          if (a.fecha_hora_registro < b.fecha_hora_registro) {
            return -1;
          }
          if (a.fecha_hora_registro > b.fecha_hora_registro) {
            return 1;
          }
        }
        return 0;
      });

      // agrupar los detalles que tengan el id_item igual al id_documento_detalle del padre
      documentosDetallesD1.forEach((documentoDetalleD1) => {
        documentoDetalleD1.documentos_detalles_d3 = documentosDetalles.filter(
          (documentoDetalle) =>
            documentoDetalle.id_item == documentoDetalleD1.id_documentos_detalle
        );
      });

      // agrupar en un objeto con los campos pagados y no_pagados como arrays, por si tiene o no nro de comprobante
      const documentosDetallesAgrupados = {
        no_pagados: [],
        pagados: [],
      };
      documentosDetallesD1.forEach((documentoDetalle) => {
        if (documentoDetalle.nro_comprobante) {
          documentosDetallesAgrupados.pagados.push(documentoDetalle);
        } else {
          documentosDetallesAgrupados.no_pagados.push(documentoDetalle);
        }
      });

      const flatMapper = (documentoDetalle) => {
        if (documentoDetalle.nivel_descargo == 1) {
          return [documentoDetalle, ...documentoDetalle.documentos_detalles_d3];
        } else {
          return [documentoDetalle];
        }
      };

      documentosDetallesMostrar = [
        ...documentosDetallesAgrupados.no_pagados.flatMap(flatMapper),
        ...documentosDetallesAgrupados.pagados.flatMap(flatMapper),
      ];

      const tbody = document.getElementById("tabla-comandas").tBodies[0];
      tbody.innerHTML = "";

      let total = 0;

      await documentosDetallesMostrar.forEach((documentoDetalle) => {
        const row = tbody.insertRow();

        row.dataset.id = documentoDetalle.id_documentos_detalle;
        row.dataset.precioUnitario = documentoDetalle.precio_unitario;
        row.dataset.cantidad = documentoDetalle.cantidad;
        row.dataset.total = documentoDetalle.precio_total;
        row.dataset.pagado =
          documentoDetalle.nro_comprobante && documentoDetalle.fecha_pago
            ? true
            : false;

        if (documentoDetalle.nro_comprobante) {
          row.classList.add("table-warning");
        }
        if (documentoDetalle.nivel_descargo == 3) {
          row.classList.add("fst-italic");
        }

        let nombreAcompanante = documentoDetalle.id_acompanate
          ? acompanantes
              .find(
                (acompanante) =>
                  acompanante.id_acompanante == documentoDetalle.id_acompanate
              )
              .apellidos_y_nombres.split(",")
              .reverse()
              .join(", ")
          : "";

        const terapista = terapistas.filter(
          (terapista) =>
            terapista.id_profesional == documentoDetalle.id_profesional
        )[0];
        const nombreTerapista = terapista
          ? `${terapista.apellidos}, ${terapista.nombres}`
          : "";

        const fechaHoraFormateada = formatearFechaYHora(documentoDetalle.fecha);

        const producto = productos.find(
          (producto) => producto.id_producto == documentoDetalle.id_producto
        );

        row.dataset.nombreProducto = producto.nombre_producto;
        row.dataset.tipo_producto = producto.tipo;

        const fechaHoraServicioFormateada =
          producto.tipo == "SRV"
            ? formatearFechaYHora(
                `${documentoDetalle.fecha_servicio}T${
                  documentoDetalle.hora_servicio
                    ? documentoDetalle.hora_servicio
                    : "00:00"
                }:00`
              )
            : "";

        row.innerHTML = `
           <td>${
             documentoDetalle.nro_habitacion
               ? `H-${documentoDetalle.nro_habitacion}`
               : "SPA"
           }</td>
           <td>${fechaHoraFormateada}</td>
           <td>${
             documentoDetalle.nivel_descargo == 3
               ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
               : ""
           } ${
          productos.find(
            (producto) => producto.id_producto == documentoDetalle.id_producto
          ).nombre_producto
        }</td>
           <td class="text-center">${documentoDetalle.cantidad}</td>
           <td class="text-end">${formatearCantidad(
             documentoDetalle.precio_unitario
           )}</td>
           <td class="text-end">${formatearCantidad(
             documentoDetalle.precio_total
           )}</td>
           <td>
             ${
               documentoDetalle.nivel_descargo == 1 &&
               !documentoDetalle.nro_comprobante
                 ? `<div class="form-check">
               <input type="checkbox" class="form-check-input" onclick="alSeleccionarPago()" checked/>
             </div>`
                 : ""
             }
           </td>
           <td class="text-truncate" style="max-width: 100px;">${
             nombreAcompanante
               ? `<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${nombreAcompanante}">`
               : ""
           }${nombreAcompanante}</span></td>
           <td class="text-truncate" style="max-width: 100px;">${
             nombreTerapista
               ? `<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="${nombreTerapista}">${nombreTerapista}</span>`
               : nombreTerapista
           }</td>
           <td>${fechaHoraServicioFormateada}</td>
           <td>${documentoDetalle.hora_final ?? ""}</td>
           <td>${documentoDetalle.observaciones ?? ""}</td>
           <td>${documentoDetalle.nro_comprobante ?? ""}</td>
           <td>${documentoDetalle.fecha_pago ?? ""}</td>
         `;

        if (
          documentoDetalle.nivel_descargo == 1 &&
          !documentoDetalle.nro_comprobante
        ) {
          filasSeleccionables.push(row);
        }

        total += parseFloat(documentoDetalle.precio_total);
      });

      agregarCeldaTotal();
      actualizarBotonCerrarCuenta();
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos de los documentos detalles",
        "consultar"
      );
    }
  }

  let seleccionarTodos = false;

  function alSeleccionarPago() {
    const totalCell = document.getElementById("total-cantidad");
    const seleccionar = document.getElementById("seleccionar");

    const estanTodosSeleccionados = filasSeleccionables.every(
      (row) => row.querySelector("input").checked
    );

    if (estanTodosSeleccionados) {
      seleccionar.textContent = "Des. Pago";
      seleccionarTodos = true;
    } else {
      seleccionar.textContent = "Sel. Pago";
      seleccionarTodos = false;
    }
    totalCell.innerHTML = formatearCantidad(calcularTotal());
  }

  function agregarCeldaTotal() {
    const tbody = document.getElementById("tabla-comandas").tBodies[0];
    const totalRow = tbody.insertRow();

    const totalCell = totalRow.insertCell();
    totalCell.colSpan = 5;
    totalCell.classList.add("text-end", "fw-bold", "fs-5", "es-el-total");
    totalCell.innerHTML = "TOTAL";

    const totalCantidadCell = totalRow.insertCell();
    totalCantidadCell.classList.add("text-end", "fw-bold", "fs-5");
    totalCantidadCell.id = "total-cantidad";
    totalCantidadCell.innerHTML = formatearCantidad(calcularTotal());
  }

  async function cargarDatosChecking() {
    const params = new URLSearchParams(window.location.search);
    const nroRegistroMaestro = params.get("nro_registro_maestro");

    const url = `${apiCheckingsUrl}?nro_registro_maestro=${nroRegistroMaestro}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      checking = data;

      if (checking.tipo_de_servicio == "HOTEL") {
        const url = `${apiRoomingUrl}?id_checkin=${checking.id_checkin}`;

        const response = await fetch(url);
        const data = await response.json();
        const rooming = data;

        document.getElementById("nro_habitacion").value = data.nro_habitacion;
      } else {
        document.getElementById("nro_habitacion").value = "---";
      }

      document.getElementById("tipo-checkin").value = checking.tipo_de_servicio;
      document.getElementById("nombre-cliente").value = checking.nombre;
      document.getElementById("nro_registro_maestro").value =
        checking.nro_registro_maestro;
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos del checking",
        "consultar"
      );
    }
  }

  let rowId = 1;

  function eliminarFila(element) {
    const fila = element.closest("tr");
    fila.remove();

    const rowId = fila.dataset.rowId;
    if (fila.dataset.tipo == "acompanante") {
      chekingRegistro.acompanantes = chekingRegistro.acompanantes.filter(
        (acompanante) => acompanante.rowId != rowId
      );
    } else {
      chekingRegistro.titular = null;
      idTitular = 0;
    }
  }

  function mostrarModalComprobante() {
    const form = document.getElementById("form-crear-comprobante");
    const montoTotal = document.querySelector(
      "#form-crear-comprobante .monto-total"
    );

    const nombre = document.getElementById("nombre");
    const tipoDocumento = document.getElementById("tipo-documento");
    const nroDocumento = document.getElementById("nro-documento");

    const direccion = document.getElementById("direccion");
    const lugar = document.getElementById("lugar");

    const mapTiposDocumento = {
      0: 1,
      1: 7,
    }; // TODO: Es temporal

    tipoDocumento.value = checking.tipo_documento
      ? checking.tipo_documento
      : mapTiposDocumento[persona.tipo_documento] ?? "";

    nombre.value = checking.razon_social
      ? checking.razon_social
      : `${persona.apellidos}, ${persona.nombres}` ?? "";
    nroDocumento.value = checking.nro_documento
      ? checking.nro_documento
      : persona.nro_documento ?? "";
    direccion.value = checking.direccion_comprobante
      ? checking.direccion_comprobante
      : persona.direccion ?? "";
    lugar.value = checking.lugar_procedencia
      ? checking.lugar_procedencia
      : persona.ciudad ?? "";

    montoTotal.value = formatearCantidad(calcularTotal());

    modalComprobante.show();
  }

  function actualizarFechaHora() {
    const fechaElemento = document.querySelector(
      "#form-crear-comprobante #fecha"
    );
    const horaElemento = document.querySelector(
      "#form-crear-comprobante #hora"
    );

    const fechaActual = new Date();

    const fechaFormateada = fechaActual.toISOString().substr(0, 10); // Formato YYYY-MM-DD
    const horaFormateada = fechaActual.toTimeString().substr(0, 5); // Formato HH:MM

    fechaElemento.value = fechaFormateada;
    horaElemento.value = horaFormateada;
  }

  function prepararCrearComprobante() {
    const botonCrearComprobante = document.getElementById("crear-comprobante");
    botonCrearComprobante.addEventListener("click", (e) => {
      e.preventDefault();

      const fecha = document.querySelector(
        "#form-crear-comprobante .fecha"
      ).value;
      const hora = document.querySelector(
        "#form-crear-comprobante .hora"
      ).value;
      const montoTotal = document.querySelector(
        "#form-crear-comprobante .monto-total"
      ).value;
      const tipoComprobante = document.querySelector(
        "#form-crear-comprobante .tipo"
      ).value;
      const tipoDocumento = document.querySelector(
        "#form-crear-comprobante .tipo-documento"
      ).value;
      const nroDocumento = document.querySelector(
        "#form-crear-comprobante .nro-documento"
      ).value;
      const nombre = document.querySelector(
        "#form-crear-comprobante .nombre"
      ).value;
      const direccion = document.querySelector(
        "#form-crear-comprobante .direccion"
      ).value;
      const lugar = document.querySelector(
        "#form-crear-comprobante .lugar"
      ).value;

      const idUsuario = "<?php echo $idUsuario ?>";

      const params = new URLSearchParams(window.location.search);
      const nroRegistroMaestro = params.get("nro_registro_maestro");

      const ids = filasSeleccionables
        .filter((row) => row.querySelector("input").checked)
        .map((row) => +row.dataset.id);

      const comprobante = {
        tipo_comprobante: tipoComprobante,
        tipo_documento_cliente: tipoDocumento,
        nro_documento_cliente: nroDocumento,
        direccion_cliente: direccion,
        lugar_cliente: lugar,
        id_usuario: +idUsuario,
        nro_registro_maestro: nroRegistroMaestro,
        nombre: nombre,
        detalles: ids,
      };

      crearComprobante(comprobante);
    });
  }

  async function cargarDatosPersona() {
    const url = `${apiPersonasUrl}/${checking.id_persona}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      persona = data;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los datos de la persona");
    }
  }

  async function crearComprobante(comprobante) {
    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(comprobante),
    };

    try {
      const response = await fetch(apiComprobantesUrl, options);
      const data = await response.json();
      console.log(data);

      const nroComprobante = data.resultado.comprobante.nro_comprobante;

      const url = `${apiReportesUrl}?tipo=generar-factura&nro_comprobante=${nroComprobante}`;
      open(url, "_blank");

      modalComprobante.hide();

      cargarDatosDocumentosDetalles();
      cargarDatosComprobantes();
      actualizarBotonCerrarCuenta();

      actualizarFilasSeleccionables();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al crear el comprobante", "crear");
    }
  }

  function actualizarBotonCerrarCuenta() {
    // si todas las filas tienen nro_comprobante y fecha_pago, habilitar el botón de cerrar cuenta, no seleccionar la ultima fila porque es el total
    let filas = document.querySelectorAll("#tabla-comandas tbody tr");
    filas = [...filas].slice(0, filas.length - 1);

    const todasLasFilasTienenNroComprobante = [...filas].every(
      (fila) => fila.dataset.pagado == "true"
    );

    const cerrarCuenta = document.getElementById("cerrar-cuenta");
    cerrarCuenta.disabled = !todasLasFilasTienenNroComprobante;
  }

  // función que comprueba que no sea solo varios guiones como por ejemplo "----"
  function limpiarGuiones(cadena) {
    if (/^[ -]*$/.test(cadena)) {
      return "";
    }
    return cadena;
  }

  async function alCambiarNroDoc() {
    const nroDocumento = document.querySelector(
      "#form-crear-comprobante .nro-documento"
    );

    nroDocumento.addEventListener("change", async (event) => {
      const tipoDocumento = document.querySelector(
        "#form-crear-comprobante .tipo-documento"
      ).value;
      const nroDocumentoValor = event.target.value;

      const tiposDocumento = {
        1: "DNI",
        6: "RUC",
      };

      const url = `${apiSunatUrl}?tipo=${tiposDocumento[tipoDocumento]}&nro=${nroDocumentoValor}`;

      const spinner = document.getElementById("spinner");
      spinner.classList.add("visible");
      spinner.classList.remove("invisible");

      try {
        const response = await fetch(url);
        const data = await response.json();
        const personaNaturalJuridica = data;

        spinner.classList.remove("visible");
        spinner.classList.add("invisible");

        const nombre = document.getElementById("nombre");
        const direccion = document.getElementById("direccion");
        const lugar = document.getElementById("lugar");

        nombre.value = personaNaturalJuridica.nombre;

        if (!limpiarGuiones(personaNaturalJuridica.direccion) == "") {
          direccion.value = limpiarGuiones(personaNaturalJuridica.direccion);
        }

        if (!limpiarGuiones(personaNaturalJuridica.lugar) == "") {
          lugar.value = limpiarGuiones(personaNaturalJuridica.lugar);
        }
      } catch (error) {
        console.error(error);
        mostrarAlert(
          "error",
          "Error al cargar los datos de la persona",
          "consultar"
        );
      }
    });
  }

  function alCerrarCuenta() {
    const cerrarCuentaBoton = document.getElementById("cerrar-cuenta");
    cerrarCuentaBoton.addEventListener("click", async (event) => {
      event.preventDefault();

      modalConfirmar.show();
    });
  }

  async function cerrarCuenta() {
    const url = `${apiCheckingsUrl}/${checking.id_checkin}/cerrar`;
    const options = {
      method: "PATCH",
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      const cerrarCuenta = data;

      if (cerrarCuenta) {
        window.location.href =
          "../relacion-clientes-hotel-spa/?ok&mensaje=La cuenta se cerró correctamente&op=editar";
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cerrar la cuenta", "editar");
    }
  }

  function mostrarModalAgregarAcompanante() {
    modalAgregarAcompanante.show();
  }

  async function crearAcompanante() {
    const apellidosYNombres = document.getElementById("acompanante");
    const sexo = document.getElementById("sexo");
    const edad = document.getElementById("edad");
    const nroOrdenUnico = acompanantes.reduce(
      (max, acompanante) =>
        acompanante.nro_de_orden_unico > max
          ? acompanante.nro_de_orden_unico
          : max,
      0
    ) + 1;

    const acompanante = {
      apellidos_y_nombres: apellidosYNombres.value,
      sexo: sexo.value,
      edad: edad.value,
      nro_registro_maestro: checking.nro_registro_maestro,
      nro_de_orden_unico: nroOrdenUnico,
    };

    const options = {
      method: "POST",
      body: JSON.stringify(acompanante),
      headers: {
        "Content-Type": "application/json",
      },
    };

    try {
      const response = await fetch(apiAcompanantesUrl, options);
      const data = await response.json();
      const acompananteCreado = data;

      if (acompananteCreado) {
        await cargarDatosAcompanantes();
        await cargarAcompanantesEnTabla();
        modalAgregarAcompanante.hide();
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al crear el acompañante", "crear");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
