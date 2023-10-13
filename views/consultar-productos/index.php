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
      <h2 class="text-center">Consulta de productos / insumos</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3">
          Buscar:
          <input
            type="text"
            class="form-control"
            id="buscar"
            placeholder="Nombre del producto"
          />
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button
            class="btn btn-outline-primary w-100 mt-3"
            onclick="buscarComprobantesPorNombre()"
          >
            <i class="fas fa-search"></i> Buscar
          </button>
        </div>
        <div class="col-md-3 ms-auto">
          <label for="tipo-producto">Tipo de producto:</label>
          <select
            class="form-select"
            id="tipo-producto"
            onchange="buscarComprobantesPorTipo(event)"
          ></select>
        </div>
      </div>

      <div class="table-responsive">
        <table id="tabla-productos" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">Tipo de insumo / Producto</th>
              <th class="text-center">P. Costo</th>
              <th class="text-center">Stock</th>
              <th class="text-center">T. Unidad</th>
              <th class="text-center">Costo total</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <button
        class="btn btn-outline-secondary"
        onclick="imprimirRegistroVentas(event)"
      >
        <i class="fas fa-print"></i> Imprimir
      </button>
    </div>
  </div>
</div>

<div
  class="modal fade modal-xl"
  id="modal-kardex"
  tabindex="-1"
  aria-labelledby="modal-kardex-label"
  style="display: none; z-index: 1056"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-kardex-label">Kardex de producto</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
          id="cerrar-modal-kardex"
        ></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-3">
            <label for="nombre_producto">Nombre del Producto:</label>
            <input
              type="text"
              class="form-control"
              id="nombre_producto"
              disabled
            />
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-auto d-flex align-items-center">
            <span>Rango de Fechas del:</span>
          </div>
          <div class="col-auto">
            <input type="date" class="form-control" id="fecha-inicio"
            name="fecha-inicio" value="<?php echo date('Y-m-d', strtotime('-1 week', strtotime(date("Y-m-d")))) ?>"
            />
          </div>
          <div class="col-auto d-flex align-items-center">
            <span>al:</span>
          </div>
          <div class="col-auto">
            <input type="date" class="form-control" id="fecha-fin"
            name="fecha-fin" value="<?php echo date("Y-m-d") ?>" />
          </div>
          <div class="col-auto">
            <button class="btn btn-outline-primary" id="btn-buscar-kardex">
              <i class="fas fa-search"></i> Buscar
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="tabla-kardex">
            <thead>
              <tr>
                <th>FECHA</th>
                <th>NRO DOC</th>
                <th>NOMBRE</th>
                <th>INGRESO</th>
                <th>SALIDA</th>
                <th>EXIST</th>
                <th>T. UND</th>
                <th>P. COSTO</th>
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

<script>
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiTiposProductosUrl = "<?php echo URL_API_NUEVA ?>/tipos-productos";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";
  const apiDocumentosDetallesUrl = "<?php echo URL_API_NUEVA ?>/documentos-detalles";

  let tablaProductosBody = null;
  let tablaKardexBody = null;

  let modalKardex = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    tablaProductosBody = document.getElementById("tabla-productos").tBodies[0];
    tablaKardexBody = document.getElementById("tabla-kardex").tBodies[0];

    modalKardex = new bootstrap.Modal(document.getElementById("modal-kardex"));

    prepararBotonBuscarKardex();

    cargarTiposProductos();
  }

  function prepararBotonBuscarKardex() {
    const btnBuscarKardex = document.getElementById("btn-buscar-kardex");
    btnBuscarKardex.addEventListener("click", buscarKardexPorFechas);
  }

  async function cargarTiposProductos() {
    try {
      const response = await fetch(apiTiposProductosUrl);
      const tipos = await response.json();

      const select = document.getElementById("tipo-producto");

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.textContent = "Seleccione un tipo de producto";
      select.appendChild(defaultOption);

      tipos.forEach((tipo) => {
        const option = document.createElement("option");
        option.value = tipo.id_tipo_producto;
        option.textContent = tipo.nombre_tipo_de_producto;
        select.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "No se pudo cargar los tipos de productos",
        "consultar"
      );
    }
  }

  function buscarComprobantesPorNombre() {
    const nombre = document.getElementById("buscar").value;
    const url = `${apiProductosUrl}?stock&nombre_producto=${nombre}`;
    buscarProductos(url);
  }

  function buscarComprobantesPorTipo(event) {
    if (!event.target.value) {
      tablaProductosBody.innerHTML = "";
      return;
    }

    const tipo = event.target.value;
    const url = `${apiProductosUrl}?stock&tipo_producto=${tipo}`;
    buscarProductos(url);
  }

  function imprimirRegistroVentas(event) {
    event.preventDefault();
    const url = `${apiReportesUrl}?tipo=registro-ventas&${prepararUrlParams()}`;
    open(url, "_blank");
  }

  function agregarFilaTotal() {
    const rowTotales = tablaProductosBody.insertRow();
    const rows = tablaProductosBody.querySelectorAll(
      "tr:not(:last-child):not(.grupo)"
    );

    const totales = {
      stock: 0,
      costoTotal: 0,
    };

    rows.forEach((row) => {
      console.log(row.cells[2].textContent);

      totales.stock += parseFloat(row.cells[2].textContent.replace(/,/g, ""));
      totales.costoTotal += parseFloat(
        row.cells[4].textContent.replace(/,/g, "")
      );
    });

    const celdaVacia1 = rowTotales.insertCell();

    const textoTotal = rowTotales.insertCell();
    textoTotal.innerHTML = "<span class='fw-bold'>TOTAL:</span>";

    const stock = rowTotales.insertCell();
    stock.classList.add("text-end");
    stock.innerHTML = `<span class='fw-bold'>${totales.stock}</span>`;

    const celdaVacia2 = rowTotales.insertCell();

    const total = rowTotales.insertCell();
    total.classList.add("text-end");
    total.innerHTML = `<span class='fw-bold'>${formatearCantidad(
      totales.costoTotal
    )}</span>`;
  }

  async function buscarProductos(url) {
    try {
      const response = await fetch(url);
      const grupos = await response.json(); // objeto de arrays

      tablaProductosBody.innerHTML = "";

      for (grupo in grupos) {
        const row = tablaProductosBody.insertRow();
        row.classList.add("grupo");

        const tipo = row.insertCell();
        tipo.colSpan = 5;
        tipo.classList.add("fw-bold");
        tipo.textContent = grupo;

        grupos[grupo].forEach((producto) => {
          const row = tablaProductosBody.insertRow();
          row.dataset.idProducto = producto.id_producto;
          row.dataset.nombreProducto = producto.nombre_producto;

          const nombre = row.insertCell();
          nombre.textContent = producto.nombre_producto;

          const costo = row.insertCell();
          costo.classList.add("text-end");
          costo.textContent = formatearCantidad(producto.costo_unitario);

          const stock = row.insertCell();
          stock.classList.add("text-end");
          stock.textContent = (+producto.stock).toFixed(0);

          const tipoUnidad = row.insertCell();
          tipoUnidad.classList.add("text-center");
          tipoUnidad.textContent = producto.tipo_de_unidad;

          const costoTotal = row.insertCell();
          costoTotal.classList.add("text-end");
          costoTotal.textContent = formatearCantidad(producto.costo_total);

          row.addEventListener("dblclick", () => {
            mostrarKardex(row.dataset);
          });
        });
      }
      agregarFilaTotal();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los comprobantes", "consultar");
    }
  }

  let productoSeleccionado = null;

  function mostrarKardex(data) {
    const nombreProducto = document.getElementById("nombre_producto");
    nombreProducto.value = data.nombreProducto;

    productoSeleccionado = data.idProducto;

    const fechaInicio = document.getElementById("fecha-inicio");
    const fechaFin = document.getElementById("fecha-fin");

    fechaInicio.value =
      "<?php echo date('Y-m-d', strtotime('-1 week', strtotime(date('Y-m-d')))) ?>";
    fechaFin.value = "<?php echo date('Y-m-d') ?>";

    modalKardex.show();
  }

  async function buscarKardexPorFechas()
  {
    const fechaInicio = document.getElementById("fecha-inicio").value;
    const fechaFin = document.getElementById("fecha-fin").value;

    const url = `${apiDocumentosDetallesUrl}?kardex&id_producto=${productoSeleccionado}&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
    
    try {
      const response = await fetch(url);
      const kardex = await response.json();
      
      tablaKardexBody.innerHTML = "";

      const acumuladoPrev = kardex[0];
      const rowAcumuladoPrev = tablaKardexBody.insertRow();
      rowAcumuladoPrev.classList.add("fw-bold");
      rowAcumuladoPrev.classList.add("text-center");

      const fechaAcumuladoPrev = rowAcumuladoPrev.insertCell();
      fechaAcumuladoPrev.classList.add("text-start");
      fechaAcumuladoPrev.textContent = "Viene:";

      const celdaVacia1 = rowAcumuladoPrev.insertCell();
      celdaVacia1.colSpan = 2;

      const ingresoAcumuladoPrev = rowAcumuladoPrev.insertCell();
      ingresoAcumuladoPrev.textContent = (+acumuladoPrev.ingreso).toFixed(0);

      const salidaAcumuladoPrev = rowAcumuladoPrev.insertCell();
      salidaAcumuladoPrev.textContent = (+acumuladoPrev.salida).toFixed(0);

      const existenciaAcumuladoPrev = rowAcumuladoPrev.insertCell();
      existenciaAcumuladoPrev.textContent = (+acumuladoPrev.existencias).toFixed(0);

      const tipoUnidadAcumuladoPrev = rowAcumuladoPrev.insertCell();
      tipoUnidadAcumuladoPrev.textContent = acumuladoPrev.tipo_de_unidad;

      const costoUnitarioAcumuladoPrev = rowAcumuladoPrev.insertCell();
      costoUnitarioAcumuladoPrev.classList.add("text-end");
      costoUnitarioAcumuladoPrev.textContent = (+acumuladoPrev.precio_unitario).toFixed(2);

      // eliminar el primer elemento del array
      kardex.shift();

      kardex.forEach((detalle) => {
        const row = tablaKardexBody.insertRow();

        const fecha = row.insertCell();
        fecha.textContent = detalle.fecha;

        const nroDoc = row.insertCell();
        nroDoc.textContent = detalle.nro_doc;

        const nombre = row.insertCell();
        nombre.textContent = !detalle.apellidos ? "" : detalle.apellidos + (detalle.nombres ? `, ${detalle.nombres}` : "");

        const ingreso = row.insertCell();
        ingreso.classList.add("text-center");
        ingreso.textContent = (+detalle.ingreso).toFixed(0);

        const salida = row.insertCell();
        salida.classList.add("text-center");
        salida.textContent = (+detalle.salida).toFixed(0);

        const existencia = row.insertCell();
        existencia.classList.add("text-center");
        existencia.textContent = (+detalle.existencias).toFixed(0);

        const tipoUnidad = row.insertCell();
        tipoUnidad.classList.add("text-center");
        tipoUnidad.textContent = detalle.tipo_de_unidad;

        const costoUnitario = row.insertCell();
        costoUnitario.classList.add("text-end");
        costoUnitario.textContent = (+detalle.precio_unitario).toFixed(2);
      });
    }
    catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar el kardex", "consultar");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
