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
      <h2 class="text-center">Listado de movimientos</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4 mb-3">
          <span>Unidad de negocio:</span>
          <select
            class="form-select"
            id="unidad-negocio"
            name="unidad-negocio"
            onchange="buscarDocumentos()"
          ></select>
        </div>
        <div class="col-md-8 mb-3"></div>
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
            <i class="fas fa-print"></i> Reporte de movimientos
          </button>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <a href="./../registro-ingreso-egreso/" class="btn btn-primary w-100">
            <i class="fas fa-add"></i> Nuevo ingreso / egreso
          </a>
        </div>
      </div>

      <h5>Listado de documentos movimiento</h5>

      <div class="table-responsive">
        <table id="tabla-documentos" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center" style="min-width: 100px">Fecha</th>
              <th class="text-center">Tipo Mov</th>
              <th class="text-center">Nro Documento</th>
              <th class="text-center">ORIGEN</th>
              <th class="text-center">DESTINO</th>
              <th class="text-center">Nombre proveedor</th>
              <th class="text-center">Motivo</th>
              <th class="text-center">Borrar</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div
  class="modal fade modal-lg"
  id="modal-ver-documento"
  tabindex="-1"
  aria-labelledby="modal-ver-documento-label"
  style="display: none; z-index: 1056"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-ver-documento-label">
          Documento movimiento
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-ver-documento"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-3">
            <label for="ver-fecha">Fecha:</label>
            <input type="text" class="form-control" id="ver-fecha" disabled />
          </div>
          <div class="col-md-3">
            <label for="ver-tipo-movimiento">Tipo de movimiento:</label>
            <input
              type="text"
              class="form-control"
              id="ver-tipo-movimiento"
              disabled
            />
          </div>
          <div class="col-md-3">
            <label for="ver-nro-doc">Nro Documento</label>
            <input type="text" class="form-control" id="ver-nro-doc" disabled />
          </div>
          <div class="col-md-3">
            <label for="ver-origen">Origen</label>
            <input type="text" class="form-control" id="ver-origen" disabled />
          </div>
          <div class="col-md-3">
            <label for="ver-destino">Destino</label>
            <input type="text" class="form-control" id="ver-destino" disabled />
          </div>
          <div class="col-md-3">
            <label for="ver-proveedor">Proveedor</label>
            <input
              type="text"
              class="form-control"
              id="ver-proveedor"
              disabled
            />
          </div>
          <div class="col-md-3">
            <label for="ver-motivo">Motivo</label>
            <input type="text" class="form-control" id="ver-motivo" disabled />
          </div>
          <div class="col-md-3">
            <label for="ver-observaciones">Observaciones</label>
            <input
              type="text"
              class="form-control"
              id="ver-observaciones"
              disabled
            />
          </div>
        </div>

        <div class="table-responsive">
          <table
            class="table table-bordered table-hover"
            id="tabla-ver-documento"
          >
            <thead>
              <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Tipo de Unidad</th>
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
          ¿Está seguro que desea borrar este documento movimiento?
        </h5>
      </div>
      <div class="modal-footer">
        <div class="row w-100">
          <button
            type="button"
            class="btn btn-danger col-md-6"
            onclick="borrarDocMovimiento(event)"
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

<script>
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";
  const apiDocumentosMovimientosUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-movimientos";
  const apiUnidadesNegocioUrl = "<?php echo URL_API_NUEVA ?>/unidades-negocio";
  const apiDocumentosDetallesUrl =
    "<?php echo URL_API_NUEVA ?>/documentos-detalles";

  let tablaDocumentosBody = null;
  let tablaDetallesBody = null;

  let modalBorrarCompra = null;
  let modalVerDocumento = null;

  let idDocumento = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    tablaDocumentosBody = document
      .getElementById("tabla-documentos")
      .querySelector("tbody");
    tablaDetallesBody = document
      .getElementById("tabla-ver-documento")
      .querySelector("tbody");

    modalBorrarCompra = new bootstrap.Modal(
      document.getElementById("modal-borrar-compra")
    );
    modalVerComprobante = new bootstrap.Modal(
      document.getElementById("modal-ver-documento")
    );

    await cargarUnidadesNegocio();
    buscarDocumentos();

    prepararBotonVerReporte();
    prepararInputsFechas();
  }

  function prepararInputsFechas() {
    const fechaInicio = document.getElementById("fecha-inicio");
    const fechaFin = document.getElementById("fecha-fin");

    fechaInicio.addEventListener("change", buscarDocumentos);
    fechaFin.addEventListener("change", buscarDocumentos);
  }

  function imprimirRegistroVentas(event) {
    event.preventDefault();
    const url = `${apiReportesUrl}?tipo=movimientos&${prepararUrlParams()}`;
    open(url, "_blank");
  }

  async function cargarUnidadesNegocio() {
    try {
      const response = await fetch(apiUnidadesNegocioUrl);
      const unidadesNegocio = await response.json();

      const selectUnidadNegocio = document.getElementById("unidad-negocio");

      unidadesNegocio.forEach((unidadNegocio) => {
        const option = document.createElement("option");
        option.value = unidadNegocio.id_unidad_de_negocio;
        option.innerHTML = unidadNegocio.nombre_unidad_de_negocio;

        selectUnidadNegocio.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar las unidades de negocio",
        "consultar"
      );
    }
  }

  function prepararUrlParams(reporte = false) {
    const fechaInicio = document.getElementById("fecha-inicio").value;
    const fechaFin = document.getElementById("fecha-fin").value;
    const unidadNegocio = document.getElementById("unidad-negocio").value;
    const usuario = '<?php echo $_SESSION["usuario"]["id_usuario"] ?>';

    let urlParams = reporte ? "tipo=movimientos&" : "";

    if (fechaInicio && fechaFin) {
      urlParams += `fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    }
    if (unidadNegocio) urlParams += `&unidad_negocio=${unidadNegocio}`;

    urlParams += `&id_usuario=${usuario}`;

    return urlParams;
  }

  async function buscarDocumentos() {
    const url = apiDocumentosMovimientosUrl + "?" + prepararUrlParams();
    await cargarDocumentos(url);
  }

  async function borrarDocMovimiento(event) {
    event.preventDefault();

    const url = `${apiDocumentosMovimientosUrl}/${idDocumento}/detalles`;
    const options = {
      method: "DELETE",
    };

    try {
      const response = await fetch(url, options);

      if (response.ok) {
        mostrarAlert(
          "ok",
          "Documento movimiento borrado correctamente",
          "borrar"
        );
        buscarDocumentos();
      } else {
        mostrarAlert(
          "error",
          "No se pudo borrar el documento movimiento",
          "borrar"
        );
      }
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo borrar el documento movimiento",
        "borrar"
      );
    }
  }

  async function mostrarModalVerDocumento(data) {
    const idDocumento = data.idDocumento;

    const verFecha = document.getElementById("ver-fecha");
    const verTipoMovimiento = document.getElementById("ver-tipo-movimiento");
    const verNroDoc = document.getElementById("ver-nro-doc");
    const verOrigen = document.getElementById("ver-origen");
    const verDestino = document.getElementById("ver-destino");
    const verProveedor = document.getElementById("ver-proveedor");
    const verMotivo = document.getElementById("ver-motivo");
    const verObservaciones = document.getElementById("ver-observaciones");

    verFecha.value = formatearFecha(data.fecha, true);
    verTipoMovimiento.value = data.tipoMovimiento;
    verNroDoc.value = data.nroDocumento;
    verOrigen.value = data.origen == "null" ? "" : data.origen;
    verDestino.value = data.destino == "null" ? "" : data.destino;
    verProveedor.value = data.nombreProveedor ?? "";
    verMotivo.value = data.motivo;
    verObservaciones.value = data.observaciones;

    const url = `${apiDocumentosDetallesUrl}?documento_movimiento=${idDocumento}`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      const detalles = data;

      tablaDetallesBody.innerHTML = "";

      detalles.forEach((documentoDetalle) => {
        const row = tablaDetallesBody.insertRow();

        row.innerHTML = `
           <td>${documentoDetalle.nombre_producto}</td>
           <td class="text-center">${documentoDetalle.cantidad}</td>
           <td class="text-center">${documentoDetalle.tipo_de_unidad ?? ""}</td>
           <td class="text-end">${formatearCantidad(documentoDetalle.precio_unitario)}</td>
           <td class="text-end">${formatearCantidad(documentoDetalle.precio_total)}</td>
         `;
      });

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

  async function cargarDocumentos(url) {
    try {
      const response = await fetch(url);
      const documentos = await response.json();

      tablaDocumentosBody.innerHTML = "";

      documentos.forEach((documento) => {
        const row = tablaDocumentosBody.insertRow();

        row.dataset.idDocumento = documento.id_documento_movimiento;

        row.dataset.fecha = documento.fecha;
        row.dataset.tipoMovimiento = documento.tipo_movimiento;
        row.dataset.nroDocumento = documento.nro_documento;
        row.dataset.origen = documento.origen;
        row.dataset.destino = documento.destino;
        row.dataset.nombreProveedor = `${documento.apellidos ?? ""} ${
          documento.nombres ?? ""
        }`;
        row.dataset.motivo = documento.motivo;
        row.dataset.observaciones = documento.observaciones;

        const fecha = row.insertCell();
        fecha.innerHTML = formatearFecha(documento.fecha);

        const tipoMovimiento = row.insertCell();
        tipoMovimiento.innerHTML = documento.tipo_movimiento;

        const nroDocumento = row.insertCell();
        nroDocumento.innerHTML = documento.nro_documento;

        const destino = row.insertCell();
        destino.innerHTML = documento.destino;

        const origen = row.insertCell();
        origen.innerHTML = documento.origen;

        const nombreProveedor = row.insertCell();
        nombreProveedor.innerHTML = `${documento.apellidos ?? ""} ${
          documento.nombres ?? ""
        }`;

        const motivo = row.insertCell();
        motivo.innerHTML = documento.motivo;

        const borrar = row.insertCell();
        borrar.classList.add("text-center");
        borrar.innerHTML = `<a href="#" class="btn btn-danger btn-sm" onclick="alBorrarDocumento(event, ${documento.id_documento_movimiento})"><i class="fas fa-trash"></i></a>`;

        row.addEventListener("click", (event) => {
          event.preventDefault();
          mostrarModalVerDocumento(row.dataset);
        });
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar los documentos movimiento",
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

  function alBorrarDocumento(event, id) {
    event.preventDefault();
    event.stopPropagation();

    idDocumento = id;
    modalBorrarCompra.show();
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
