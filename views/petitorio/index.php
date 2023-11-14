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
        <div class="col-md-2">
          TEMPORADA:
          <select
            id="temporada"
            name="temporada"
            class="form-select"
            onchange="actualizarColoresProductos()"
          >
            <option value="baja">Temporada Baja</option>
            <option value="alta">Temporada Alta</option>
          </select>
        </div>
        <div class="col-md-2">
          CENTRAL DE COSTOS:
          <select
            id="central-costos"
            name="central-costos"
            class="form-select"
            onchange="alCambiarCentralCostos()"
          ></select>
        </div>
        <div class="col-md-2">
          X CÓDIGO
          <input
            type="text"
            id="codigo"
            name="codigo"
            class="form-control"
            placeholder="Ingrese el código"
            onchange="alCambiarCodigo()"
          />
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button
            class="btn btn-primary"
            onclick="alBuscar()"
          >
            Buscar
          </button>
        </div>

        <div class="col-md-auto d-flex align-items-end ms-auto">
          <div class="form-check form-switch">
            <input
              class="form-check-input"
              type="checkbox"
              id="solo-pedidos"
              onchange="alCambiarSoloPedidos()"
            />
            <label class="form-check-label" for="solo-pedidos">
              SOLO PEDIDO
            </label>
          </div>
        </div>
      </div>

      <div class="table-responsive tabla-petitorio-container">
        <table id="tabla-petitorio" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center" colspan="2">Central de costos</th>
              <th class="text-center" colspan="2">Temporada Baja</th>
              <th class="text-center" colspan="2">Temporada Alta</th>
              <th class="text-center" colspan="3"></th>
              <th colspan="3" style="background-color: white !important"></th>
            </tr>
            <tr>
              <th class="text-center">Código</th>
              <th class="text-center">Nombre del producto</th>
              <th class="text-center">Stock Min</th>
              <th class="text-center">Stock Max</th>
              <th class="text-center">Stock Min</th>
              <th class="text-center">Stock Max</th>
              <th class="text-center">Costo unitario</th>
              <th class="text-center">Stock actual</th>
              <th class="text-center">T. Unidad</th>
              <th style="width: 40px; background-color: white !important"></th>
              <th class="text-center">Cant. Pedido</th>
              <th class="text-center">Costo Total</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <!-- sección total -->
      <div class="row mt-3">
        <div class="col-auto ms-auto d-flex align-items-center pe-0">
          TOTAL:
        </div>
        <div class="col-auto">
          <input
            type="text"
            id="total"
            name="total"
            class="form-control"
            readonly
          />
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-2">
          <button class="btn btn-primary" onclick="limpiarPedido()">
            Limpiar pedido
          </button>
        </div>
        <div class="col-md-2 ms-auto">
          <button class="btn btn-outline-secondary" onclick="imprimirPedido()">
            Imprimir pedido
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const apiCentralesCostosUrl = "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/reportes";

  let tablaProductosBody = null;

  let centralesCostosCargados = [];
  let centralesConProductos = [];

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    tablaProductosBody = document.getElementById("tabla-petitorio").tBodies[0];

    await cargarCentralesCostos();
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

      select.addEventListener("change", alCambiarCentralCostos);
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los grupos", "consultar");
    }
  }

  function alCambiarSoloPedidos() {
    const soloPedidos = document.getElementById("solo-pedidos").checked;

    if (soloPedidos) {
      const url = `${apiProductosUrl}?con-centrales-costos&solo-pedido`;

      cargarCentralesConProductosEnTabla(url);
      return;
    } else {
      alBuscar();
    }
  }

  function alBuscar() {
    const centralCostosSelect = document.getElementById("central-costos");
    const centralCostos = centralCostosSelect.value;

    const codigoInput = document.getElementById("codigo");
    const codigo = codigoInput.value;

    const soloPedidos = document.getElementById("solo-pedidos");
    soloPedidos.checked = false;

    if (!centralCostos && !codigo) {
      mostrarAlert(
        "error",
        "Debe ingresar un código o seleccionar una central de costos",
        "consultar"
      );
      return;
    }

    const url = `${apiProductosUrl}?con-centrales-costos${
      centralCostos ? `&central-costos=${centralCostos}` : ""
    }${codigo ? `&codigo=${codigo}` : ""}`;

    cargarCentralesConProductosEnTabla(url);
  }

  function alCambiarCentralCostos() {
    const codigoInput = document.getElementById("codigo");
    codigoInput.value = "";
  }

  function alCambiarCodigo() {
    const centralCostosSelect = document.getElementById("central-costos");
    centralCostosSelect.value = "";
  }

  async function cargarCentralesConProductosEnTabla(url) {
    tablaProductosBody.innerHTML = "";

    try {
      const response = await fetch(url);
      const data = await response.json();

      centralesConProductos = data;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar los productos", "consultar");
    }

    centralesConProductos.forEach((element) => {
      const tr = tablaProductosBody.insertRow();

      const tdCosto = tr.insertCell();
      tdCosto.textContent = element.nombre_del_costo;
      tdCosto.classList.add("fw-bold");
      tdCosto.colSpan = 7;

      mostrarProductos(element.productos);
    });

    actualizarTotal();
  }

  function actualizarColoresProductos() {
    const temporadaSelect = document.getElementById("temporada");
    const temporada = temporadaSelect.value;

    const trs = tablaProductosBody.querySelectorAll("tr");

    trs.forEach((tr) => {
      const tds = tr.querySelectorAll("td");

      if (tds.length == 1) {
        return;
      }

      const tdStock = tds[7];
      let tdStockMax = null;

      if (temporada == "baja") {
        tdStockMax = tds[3];
      } else if (temporada == "alta") {
        tdStockMax = tds[5];
      }

      // marcar las columnas usadas según la temporada
      if (temporada == "baja") {
        tds[2].style.backgroundColor = "lightgreen";
        tds[3].style.backgroundColor = "lightgreen";
        tds[4].style.backgroundColor = "unset";
        tds[5].style.backgroundColor = "unset";
      } else if (temporada == "alta") {
        tds[2].style.backgroundColor = "unset";
        tds[3].style.backgroundColor = "unset";
        tds[4].style.backgroundColor = "lightgreen";
        tds[5].style.backgroundColor = "lightgreen";
      }

      if (tdStock.textContent < tdStockMax.textContent) {
        tdStock.classList.add("bg-danger");
      } else {
        tdStock.classList.remove("bg-danger");
      }
    });
  }

  function mostrarProductos(productos) {
    const temporadaSelect = document.getElementById("temporada");
    const temporada = temporadaSelect.value;

    productos.forEach((element) => {
      const tr = tablaProductosBody.insertRow();

      const tdCodigo = tr.insertCell();
      tdCodigo.textContent = element.codigo;

      const tdNombre = tr.insertCell();
      tdNombre.textContent = element.nombre_producto;

      const tdStockMinBaja = tr.insertCell();
      tdStockMinBaja.textContent = element.stock_min_temporada_baja;
      tdStockMinBaja.style.backgroundColor = "lightgreen";
      tdStockMinBaja.classList.add("text-center");

      const tdStockMaxBaja = tr.insertCell();
      tdStockMaxBaja.textContent = element.stock_max_temporada_baja;
      tdStockMaxBaja.style.backgroundColor = "lightgreen";
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

      const tdStock = tr.insertCell();
      tdStock.textContent = formatearCantidad(element.stock);
      tdStock.classList.add("text-center");
      if (
        temporada == "baja" &&
        element.stock < element.stock_max_temporada_baja
      ) {
        tdStock.style.backgroundColor = "lightsalmon";
      }
      if (
        temporada == "alta" &&
        element.stock < element.stock_max_temporada_alta
      ) {
        tdStock.style.backgroundColor = "lightsalmon";
      }
      tdStock.classList.add("fw-bold");

      const tdTipoUnidad = tr.insertCell();
      tdTipoUnidad.classList.add("text-center");
      tdTipoUnidad.textContent = element.tipo_de_unidad;

      const tdVacio = tr.insertCell();
      tdVacio.style.width = "40px !important";

      const tdCantidadPedido = tr.insertCell();
      tdCantidadPedido.classList.add("text-center");

      const tdCostoTotal = tr.insertCell();
      tdCostoTotal.textContent = formatearCantidad(
        element.costo_unitario * element.cantidad_pedido
      );

      const inputCantidadPedido = document.createElement("input");
      inputCantidadPedido.type = "number";
      inputCantidadPedido.classList.add("form-control");
      inputCantidadPedido.min = 0;
      inputCantidadPedido.value = element.cantidad_pedido;
      inputCantidadPedido.addEventListener("change", async (e) => {
        const cantidad = e.target.value;
        const costoTotal = cantidad * element.costo_unitario;
        tdCostoTotal.textContent = formatearCantidad(costoTotal);

        await actualizarCantidadPedido(element.id_producto, cantidad);
      });
      tdCantidadPedido.appendChild(inputCantidadPedido);
    });
  }

  async function actualizarCantidadPedido(id, cantidad) {
    const url = `${apiProductosUrl}/${id}`;
    const body = {
      cantidad_pedido: cantidad,
    };
    const options = {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);
      actualizarTotal();
      mostrarAlert(
        "ok",
        "Se actualizó la cantidad del producto correctamente",
        "editar"
      );
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al actualizar la cantidad del producto",
        "editar"
      );
    }
  }

  async function limpiarPedido() {
    const url = `${apiProductosUrl}/0/limpiar-pedido`;
    const options = {
      method: "PATCH",
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();

      console.log(data);

      tablaProductosBody.innerHTML = "";
      actualizarTotal();

      mostrarAlert("ok", "Se limpió el pedido correctamente", "editar");
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al limpiar el pedido", "editar");
    }
  }

  function actualizarTotal() {
    const trs = tablaProductosBody.querySelectorAll("tr");

    let total = 0;

    trs.forEach((tr) => {
      const tds = tr.querySelectorAll("td");

      if (tds.length == 1) {
        return;
      }

      const tdCostoTotal = tds[11];
      total += parseFloat(tdCostoTotal.textContent);
    });

    const inputTotal = document.getElementById("total");
    inputTotal.value = formatearCantidad(total);
  }

  async function imprimirPedido() {}

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
