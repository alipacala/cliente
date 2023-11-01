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
      <h2 class="text-center">Petitorio</h2>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3">
          CENTRAL DE COSTOS:
          <select
            id="central-costos"
            name="central-costos"
            class="form-select"
          ></select>
        </div>
      </div>

      <div class="table-responsive">
        <table
          id="tabla-listado-catalogo"
          class="table table-bordered table-hover"
        >
          <thead>
            <tr>
              <th class="text-center">Central de costos</th>
              <th class="text-center"></th>
              <th class="text-center" colspan="2">Temporada Baja</th>
              <th class="text-center" colspan="2">Temporada Alta</th>
              <th class="text-center"></th>
            </tr>
            <tr>
              <th class="text-center">Código</th>
              <th class="text-center">Nombre del producto</th>
              <th class="text-center">Stock Min</th>
              <th class="text-center">Stock Max</th>
              <th class="text-center">Stock Min</th>
              <th class="text-center">Stock Max</th>
              <th class="text-center">Costo unitario</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  const apiCentralesCostosUrl = "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";

  let tablaProductosBody = null;

  let centralesCostosCargados = [];
  let centralesConProductos = [];

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    tablaProductosBody = document.getElementById("tabla-listado-catalogo")
      .tBodies[0];

    await cargarCentralesCostos();
    await cargarProductos();
    await cargarCentralesConProductosEnTabla();
  }

  async function cargarCentralesCostos() {
    try {
      const response = await fetch(apiCentralesCostosUrl);
      const data = await response.json();

      const select = document.getElementById("central-costos");

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una central de costos";
      defaultOption.selected = true;
      select.appendChild(defaultOption);

      centralesCostosCargados = data;

      centralesCostosCargados.forEach((element) => {
        const option = document.createElement("option");
        option.value = element.id_central_de_costos;
        option.text = element.nombre_del_costo;
        select.appendChild(option);
      });

      select.addEventListener("change", cargarCentralesConProductosEnTabla);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos", "consultar");
    }
  }

  async function cargarProductos() {
    const url = `${apiProductosUrl}?con-centrales-costos`;

    try {
      const response = await fetch(url);
      const data = await response.json();
      centralesConProductos = data;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
    }
  }

  async function cargarCentralesConProductosEnTabla() {
    tablaProductosBody.innerHTML = "";

    const centralCostosSelect = document.getElementById("central-costos");
    const centralCostos = centralCostosSelect.value;

    if (!centralCostos) {
      centralesConProductos.forEach((element) => {
        const tr = tablaProductosBody.insertRow();

        const tdCosto = tr.insertCell();
        tdCosto.textContent = element.nombre_del_costo;
        tdCosto.classList.add("fw-bold");
        tdCosto.colSpan = 7;

        mostrarProductos(element.productos);
      });
      return;
    }

    const centralCostosFiltrado = centralesConProductos.find(
      (element) => element.id_central_de_costos == centralCostos
    );

    if (!centralCostosFiltrado) {
      return;
    }

    const tr = tablaProductosBody.insertRow();

    const tdCosto = tr.insertCell();
    tdCosto.textContent = centralCostosFiltrado.nombre_del_costo;
    tdCosto.classList.add("fw-bold");
    tdCosto.colSpan = 7;

    mostrarProductos(centralCostosFiltrado.productos);
  }

  function mostrarProductos(productos) {
    productos.forEach((element) => {
      const tr = tablaProductosBody.insertRow();

      const tdCodigo = tr.insertCell();
      tdCodigo.textContent = element.codigo;

      const tdNombre = tr.insertCell();
      tdNombre.textContent = element.nombre_producto;

      const tdStockMinBaja = tr.insertCell();
      tdStockMinBaja.textContent = element.stock_min_temporada_baja;
      tdStockMinBaja.classList.add("text-center");

      const tdStockMaxBaja = tr.insertCell();
      tdStockMaxBaja.textContent = element.stock_max_temporada_baja;
      tdStockMaxBaja.classList.add("text-center");

      const tdStockMinAlta = tr.insertCell();
      tdStockMinAlta.textContent = element.stock_min_temporada_alta;
      tdStockMinAlta.classList.add("text-center");

      const tdStockMaxAlta = tr.insertCell();
      tdStockMaxAlta.textContent = element.stock_max_temporada_alta;
      tdStockMaxAlta.classList.add("text-center");

      const tdCostoUnitario = tr.insertCell();
      tdCostoUnitario.textContent = formatearCantidad(element.costo_unitario);
      tdCostoUnitario.classList.add("text-end");
    });
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
