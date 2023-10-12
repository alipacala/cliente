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

<script>
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiTiposProductosUrl = "<?php echo URL_API_NUEVA ?>/tipos-productos";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";

  let tablaProductosBody = null;

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    tablaProductosBody = document.getElementById("tabla-productos").tBodies[0];

    cargarTiposProductos();
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
      "tr:not(:last-child):not(.table-secondary)"
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
        row.classList.add("table-secondary");

        const tipo = row.insertCell();
        tipo.colSpan = 5;
        tipo.classList.add("fw-bold");
        tipo.textContent = grupo;

        grupos[grupo].forEach((producto) => {
          const row = tablaProductosBody.insertRow();

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
        });
      }
      agregarFilaTotal();
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "No se pudo cargar los comprobantes", "consultar");
    }
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
