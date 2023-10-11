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
      <h2 class="text-center">Reporte de Compras y Gastos</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-6 d-flex align-items-center">
          <div class="row">
            <div class="col-auto d-flex align-items-center">
              <span>Rango de Fechas del:</span>
            </div>
            <div class="col-auto">
              <input type="date" class="form-control" id="fecha-inicio"
              name="fecha-inicio" value="<?php echo date("Y-m-d") ?>" />
            </div>
            <div class="col-auto d-flex align-items-center">
              <span>al:</span>
            </div>
            <div class="col-auto">
              <input type="date" class="form-control" id="fecha-fin"
              name="fecha-fin" value="<?php echo date("Y-m-d") ?>" />
            </div>
          </div>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button class="btn btn-outline-secondary w-100" id="btn-reporte">
            <i class="fas fa-print"></i> Reporte de cuentas por pagar
          </button>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <a href="./../registrar-nueva-compra/" class="btn btn-primary w-100">
            <i class="fas fa-add"></i> Nueva compra
          </a>
        </div>
      </div>

      <h5>Cuentas por pagar</h5>

      <div class="table-container">
        <table id="tabla-comprobantes" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center" style="min-width: 100px">Fecha</th>
              <th class="text-center">Nro Comprobante</th>
              <th class="text-center">RUC</th>
              <th class="text-center">Proveedor</th>
              <th class="text-center">Subtotal</th>
              <th class="text-center">IGV</th>
              <th class="text-center">Total</th>
              <th class="text-center">Percepción</th>
              <th class="text-center">Gran Total</th>
              <th class="text-center">Estado</th>
              <th class="text-center">X Pagar</th>
              <th class="text-center">Borrar</th>
              <th class="text-center">Pagar</th>
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
  id="modal-borrar-compra"
  tabindex="-1"
  aria-labelledby="modal-borrar-compra-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h5 class="modal-title" id="modal-borrar-compra-label">
          ¿Está seguro que desea borrar este comprobante de compra?
        </h5>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-danger col-md-6"
            onclick="borrarComprobante(event)"
            data-bs-dismiss="modal"
          >
            Sí
          </button>
          <button
            type="button"
            class="btn btn-outline-secondary col-md-6"
            id="confirmar-borrar-compra"
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
  class="modal fade modal-lg"
  id="modal-ver-comprobante"
  tabindex="-1"
  aria-labelledby="modal-ver-comprobante-label"
  style="display: none; z-index: 1056"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-ver-comprobante-label">
          Comprobante de compra
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
  class="modal modal-lg fade"
  id="modal-pago"
  tabindex="-1"
  aria-labelledby="modal-pago-label"
  style="display: none"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-pago-label">Registro de pago</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-pago"
        ></button>
      </div>
      <div class="modal-body">
        <form id="form-crear-pago">
          <div class="row">
            <div class="col-md-3">
              <label for="tipo_doc">Tipo Doc:</label>
              <input class="form-control" id="tipo_doc" disabled />
            </div>
            <div class="col-md-3">
              <label for="nro_comprobante">Nro de comprobante:</label>
              <input class="form-control" id="nro_comprobante" disabled />
            </div>
            <div class="col-md-4 ms-auto mb-3">
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
            <span class="col-form-label col-md-4 mb-3">Doc Identidad</span>
            <div class="col-md-8 mb-3">
              <input
                type="text"
                class="form-control"
                id="doc_identidad"
                name="doc_identidad"
                disabled
              />
            </div>
            <span class="col-form-label col-md-4 mb-3">Proveedor</span>
            <div class="col-md-8 mb-3">
              <input
                type="text"
                class="form-control"
                id="proveedor"
                name="proveedor"
                disabled
              />
            </div>
            <span class="col-form-label col-md-4 mb-3">Tipo de gasto</span>
            <div class="col-md-8 mb-3">
              <input
                type="text"
                class="form-control"
                id="tipo_gasto"
                name="tipo_gasto"
                disabled
              />
            </div>
            <span class="col-form-label col-md-4 mb-3">Items</span>
            <div class="col-md-8 mb-3">
              <button
                type="button"
                class="btn btn-outline-primary"
                id="btn-ver-detalles"
              >
                Ver detalles
              </button>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-4">
              <label for="origen-pago">Origen de Pago</label>
              <select class="form-select" id="origen-pago"></select>
            </div>
            <div class="col-md-4">
              <label for="medio-pago">Forma de Pago</label>
              <select class="form-select" id="medio-pago">
                <option value="EFE">Efectivo</option>
                <option value="YAP">Yape</option>
                <option value="PLI">Plin</option>
                <option value="TJT">Tarjeta</option>
                <option value="DEP">Depósito</option>
                <option value="TRA">Transferencia</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="nro_voucher">Nro de Voucher</label>
              <input type="text" class="form-control" id="nro_voucher" disabled />
            </div>
            <div class="col-md-4">
              <label for="fecha">Fecha</label>
              <input type="date" class="form-control" id="fecha" value="<?php echo date("Y-m-d") ?>"
              />
            </div>
            <div class="col-md-4">
              <label for="monto">Monto</label>
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
              id="registrar-pago"
              onclick="registrarPago()"
            >
              ACEPTAR
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
  const apiComprobantesVentasUrl =
    "<?php echo URL_API_NUEVA ?>/comprobantes-ventas";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";
  const apiComprobantesDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/comprobantes-detalles";
  const apiCajasUrl = "<?php echo URL_API_NUEVA ?>/cajas";
  const apiRecibosUrl = "<?php echo URL_API_NUEVA ?>/recibos-pago";

  let tablaComprobantesBody = null;

  let modalBorrarCompra = null;
  let modalVerComprobante = null;
  let modalPago = null;

  let idCompra = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    cargarCajas();

    tablaComprobantesBody = document
      .getElementById("tabla-comprobantes")
      .querySelector("tbody");

    modalBorrarCompra = new bootstrap.Modal(
      document.getElementById("modal-borrar-compra")
    );
    modalVerComprobante = new bootstrap.Modal(
      document.getElementById("modal-ver-comprobante")
    );
    modalPago = new bootstrap.Modal(document.getElementById("modal-pago"));

    buscarComprobantes();

    prepararBotonVerReporte();
    prepararInputsFechas();
    prepararSelectMedioPago();
  }

  async function cargarCajas() {
    try {
      const response = await fetch(apiCajasUrl);
      const cajas = await response.json();

      const selectOrigenPago = document.getElementById("origen-pago");

      cajas.forEach((caja) => {
        const option = document.createElement("option");
        option.value = caja.nro_de_caja;
        option.textContent = caja.nombre_caja;

        selectOrigenPago.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar las cajas", "consultar");
    }
  }

  function prepararInputsFechas() {
    const fechaInicio = document.getElementById("fecha-inicio");
    const fechaFin = document.getElementById("fecha-fin");

    fechaInicio.addEventListener("change", buscarComprobantes);
    fechaFin.addEventListener("change", buscarComprobantes);
  }

  function imprimirRegistroVentas(event) {
    event.preventDefault();
    const url = `${apiReportesUrl}?tipo=cuentas-por-pagar&${prepararUrlParams()}`;
    open(url, "_blank");
  }

  function agregarFilaTotal() {
    const rowTotales = tablaComprobantesBody.insertRow();
    const rows = tablaComprobantesBody.querySelectorAll("tr:not(:last-child)");

    const totales = {
      subtotal: 0,
      igv: 0,
      total: 0,
      percepcion: 0,
      granTotal: 0,
      porPagar: 0,
    };

    rows.forEach((row) => {
      totales.subtotal += parseFloat(
        row.cells[4].textContent.replace(/,/g, "")
      );
      totales.igv += parseFloat(row.cells[5].textContent.replace(/,/g, ""));
      totales.total += parseFloat(row.cells[6].textContent.replace(/,/g, ""));
      totales.percepcion += parseFloat(
        row.cells[7].textContent.replace(/,/g, "")
      );
      totales.granTotal += parseFloat(
        row.cells[8].textContent.replace(/,/g, "")
      );
      totales.porPagar += parseFloat(
        row.cells[10].textContent.replace(/,/g, "")
      );
    });

    const celdaVacia1 = rowTotales.insertCell();
    celdaVacia1.colSpan = 3;
    const textoTotal = rowTotales.insertCell();
    textoTotal.innerHTML = "<span class='fw-bold'>TOTAL:</span>";

    const subtotal = rowTotales.insertCell();
    subtotal.classList.add("text-end");
    subtotal.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.subtotal
    )}</span>`;

    const igv = rowTotales.insertCell();
    igv.classList.add("text-end");
    igv.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.igv
    )}</span>`;

    const total = rowTotales.insertCell();
    total.classList.add("text-end");
    total.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.total
    )}</span>`;

    const percepcion = rowTotales.insertCell();
    percepcion.classList.add("text-end");
    percepcion.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.percepcion
    )}</span>`;

    const granTotal = rowTotales.insertCell();
    granTotal.classList.add("text-end");
    granTotal.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.granTotal
    )}</span>`;

    const celdaVacia2 = rowTotales.insertCell();

    const porPagar = rowTotales.insertCell();
    porPagar.classList.add("text-end");
    porPagar.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.porPagar
    )}</span>`;

    const celdaVacia3 = rowTotales.insertCell();
    celdaVacia3.colSpan = 2;
  }

  function prepararUrlParams(reporte = false) {
    const fechaInicio = document.getElementById("fecha-inicio").value;
    const fechaFin = document.getElementById("fecha-fin").value;
    const usuario = '<?php echo $_SESSION["usuario"]["id_usuario"] ?>';

    let urlParams = reporte ? "tipo=compras" : "compras";

    if (fechaInicio && fechaFin) {
      urlParams += `&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }

    urlParams += `&id_usuario=${usuario}`;

    return urlParams;
  }

  async function buscarComprobantes() {
    const url = apiComprobantesVentasUrl + "?" + prepararUrlParams();

    await cargarComprobantes(url);
    agregarFilaTotal();
  }

  async function borrarComprobante(event) {
    event.preventDefault();

    const url = `${apiComprobantesVentasUrl}/${idCompra}/compra`;
    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(url, options);

      if (response.ok) {
        mostrarAlert("ok", "Comprobante borrado correctamente", "borrar");
        buscarComprobantes();
      } else {
        mostrarAlert("error", "No se pudo borrar el comprobante", "borrar");
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo borrar el comprobante", "borrar");
    }
  }

  async function cargarComprobantes(url) {
    try {
      const response = await fetch(url);
      const comprobantes = await response.json();

      limpiarTabla();

      comprobantes.forEach((comprobante) => {
        const row = tablaComprobantesBody.insertRow();

        row.dataset.por_pagar = comprobante.por_pagar;
        row.dataset.id_comprobante = comprobante.id_comprobante;

        row.dataset.fechaComprobante = comprobante.fecha;
        row.dataset.tipoComprobante = comprobante.tipo_comprobante;
        row.dataset.nroComprobante = comprobante.nro_comprobante;
        row.dataset.docCliente = comprobante.ruc;
        row.dataset.cliente = comprobante.proveedor;
        row.dataset.granTotal = comprobante.gran_total;

        row.dataset.tipoGasto = comprobante.nombre_gasto;

        const fecha = row.insertCell();
        fecha.innerHTML = formatearFecha(comprobante.fecha);

        const nroComprobante = row.insertCell();
        nroComprobante.innerHTML = comprobante.nro_comprobante;

        const ruc = row.insertCell();
        ruc.innerHTML = comprobante.ruc;

        const proveedor = row.insertCell();
        proveedor.innerHTML = comprobante.proveedor;

        const subtotal = row.insertCell();
        subtotal.classList.add("text-end");
        subtotal.innerHTML = formatearCantidad(comprobante.subtotal);

        const igv = row.insertCell();
        igv.classList.add("text-end");
        igv.innerHTML = formatearCantidad(comprobante.igv);

        const total = row.insertCell();
        total.classList.add("text-end");
        total.innerHTML = formatearCantidad(comprobante.total);

        const percepcion = row.insertCell();
        percepcion.classList.add("text-end");
        percepcion.innerHTML = formatearCantidad(comprobante.percepcion);

        const granTotal = row.insertCell();
        granTotal.classList.add("text-end");
        granTotal.innerHTML = formatearCantidad(comprobante.gran_total);

        const estado = row.insertCell();
        estado.classList.add("text-center");
        estado.innerHTML = comprobante.estado;

        const porPagar = row.insertCell();
        porPagar.classList.add("text-end");
        porPagar.innerHTML = formatearCantidad(comprobante.por_pagar);

        const borrar = row.insertCell();
        borrar.classList.add("text-center");
        borrar.innerHTML = `<a href="#" class="btn btn-danger btn-sm" onclick="alBorrarComprobante(event, ${comprobante.id_comprobante})"><i class="fas fa-trash"></i></a>`;

        const pagar = row.insertCell();
        pagar.classList.add("text-center");
        pagar.innerHTML = `<a href="#" onclick="mostrarModalRecibo(event, ${comprobante.id_comprobante})" class="btn btn-primary btn-sm">PAGAR</a>`;

        // al hacer click en la fila se muestra el modal comprobante
        row.addEventListener("click", (event) => {
          event.preventDefault();
          mostrarModalVerComprobante(row.dataset);
        });
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar los comprobantes de compra",
        "consultar"
      );
    }
  }

  function prepararBotonVerReporte() {
    const btnReporte = document.getElementById("btn-reporte");
    btnReporte.addEventListener("click", () => {
      open(`${apiReportesUrl}?${prepararUrlParams(true)}`, "_blank");
    });
  }

  async function mostrarModalVerComprobante(data) {
    const idComprobante = data.id_comprobante;
    const total = data.granTotal;

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

    const url = `${apiComprobantesDetallesUrl}?comprobante_compra=${idComprobante}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      const detalles = data;

      const tbody = document.getElementById("tabla-ver-comprobante").tBodies[0];
      tbody.innerHTML = "";

      detalles.forEach((documentoDetalle) => {
        const row = tbody.insertRow();

        row.innerHTML = `
           <td>${documentoDetalle.cantidad}</td>
           <td>${documentoDetalle.descripcion}</td>
           <td>${documentoDetalle.precio_unitario}</td>
           <td>${documentoDetalle.precio_total}</td>
         `;
      });

      tbody.insertRow().innerHTML = `
         <td colspan="3" class="fw-bold text-end">TOTAL</td>
         <td class="fw-bold">${total}</td>
       `;

      modalVerComprobante.show();

      // mostrar el overlay del modal delante del otro modal
      if (document.querySelectorAll(".modal-backdrop")[1]) {
        document.querySelectorAll(".modal-backdrop")[1].style.zIndex = 1055;
      }
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar los datos del comprobante",
        "consultar"
      );
    }
  }

  function prepararSelectMedioPago() {
    const medioPago = document.getElementById("medio-pago");
    medioPago.addEventListener("change", (event) => {
      const nroComprobante = document.getElementById("nro_voucher");
      if (event.target.value == "EFE") {
        nroComprobante.disabled = true;
        nroComprobante.value = "";
      } else {
        nroComprobante.disabled = false;
      }
    });
  }

  function mostrarModalRecibo(event, idComprobante) {
    event.stopPropagation();
    const row = event.target.closest("tr");

    idCompra = idComprobante;

    const tipoComprobante = document.getElementById("tipo_doc");
    const nroComprobante = document.getElementById("nro_comprobante");
    const total = document.getElementById("total");

    const docIdentidad = document.getElementById("doc_identidad");
    const proveedor = document.getElementById("proveedor");
    const tipoGasto = document.getElementById("tipo_gasto");
    const monto = document.getElementById("monto");

    const btnVerDetalles = document.getElementById("btn-ver-detalles");

    tipoComprobante.value = row.dataset.tipoComprobante;
    nroComprobante.value = row.dataset.nroComprobante;
    total.value = row.dataset.por_pagar;

    docIdentidad.value = row.dataset.docCliente;
    proveedor.value = row.dataset.cliente;
    tipoGasto.value = `Compra ${row.dataset.tipoGasto}`;
    monto.value = row.dataset.por_pagar;

    btnVerDetalles.addEventListener("click", () => {
      mostrarModalVerComprobante(row.dataset);
    });

    modalPago.show();
  }

  async function registrarPago() {
    const recibo = {
      id_comprobante_ventas: idCompra,
      nro_de_caja: document.getElementById("origen-pago").value,
      id_usuario: '<?php echo $_SESSION["usuario"]["id_usuario"] ?>',
      medio_pago: document.getElementById("medio-pago").value,
      fecha: document.getElementById("fecha").value,
      total: document.getElementById("monto").value,
      esCompra: true,
    };

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(recibo),
    };

    try {
      const response = await fetch(apiRecibosUrl, options);
      const data = await response.json();

      if (response.ok) {
        mostrarAlert("ok", "Pago registrado correctamente", "registrar");
        buscarComprobantes();
        modalPago.hide();
      } else {
        mostrarAlert("error", data.mensaje, "registrar");
      }
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo registrar el pago", "registrar");
    }
  }

  function alBorrarComprobante(event, id) {
    event.preventDefault();
    event.stopPropagation();

    idCompra = id;
    modalBorrarCompra.show();
  }

  function limpiarTabla() {
    const tbody = document.getElementById("tabla-comprobantes").tBodies[0];
    tbody.innerHTML = "";
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
