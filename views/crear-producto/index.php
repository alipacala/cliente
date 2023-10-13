<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido >
TIEMPO_INACTIVIDAD)) { session_unset(); session_destroy(); } $logueado =
isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado); $editar = isset($_GET["id"]) ?
$_GET["id"] : false; ?>

<div class="container my-5 main-cont">
  <div id="alert-place"></div>
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">
        <?php echo $editar ? "Editar" : "Crear" ?>
        Ficha de Producto o Insumo
      </h2>
    </div>
    <div class="card-body">
      <form id="form-crear-producto">
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="nombre_producto">Nombre del Producto</label>
            <input
              type="text"
              class="form-control"
              id="nombre_producto"
              name="nombre_producto"
              required
            />
          </div>

          <div class="form-group col-md-4">
            <label for="codigo">Código</label>
            <input
              type="text"
              class="form-control"
              id="codigo"
              name="codigo"
              required
            />
          </div>
        </div>

        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="clasificacion_ventas"
              >Clasificación en Catálogo de Ventas</label
            >
            <select
              class="form-select"
              id="clasificacion_ventas"
              name="clasificacion_ventas"
              required
            ></select>
          </div>
          <div class="form-group col-md-4">
            <label for="central_costos">Central de Costos</label>
            <select
              class="form-select"
              id="central_costos"
              name="central_costos"
              required
            ></select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="tipo_producto">Tipo de Producto</label>
            <select
              class="form-select"
              id="tipo_producto"
              name="tipo_producto"
            ></select>
          </div>
          <div class="form-group col-md-4">
            <label for="fecha_vigencia">Fecha de Vigencia del Producto</label>
            <input type="date" class="form-control" id="fecha_vigencia"
            name="fecha_vigencia" value="<?php echo date("Y-m-d") ?>" required
            />
          </div>
          <div class="form-group col-md-4">
            <label for="tipo_de_unidad">Tipo de Unidad</label>
            <select
              class="form-select"
              id="tipo_de_unidad"
              name="tipo_de_unidad"
              required
            >
              <option value="UN" selected>UNIDAD</option>
              <option value="KG">KILO</option>
              <option value="GR">GRAMO</option>
              <option value="LT">LITRO</option>
              <option value="ML">MILILITRO</option>
              <option value="OZ">ONZA</option>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="cantidad_de_fracciones"
              >Cantidad de Unidades de Fracciones</label
            >
            <input
              type="text"
              class="form-control"
              id="cantidad_de_fracciones"
              name="cantidad_de_fracciones"
            />
          </div>

          <div class="form-group col-md-4">
            <label for="tipo_de_unidad_de_fracciones"
              >Tipo de Unidad de Fracciones</label
            >
            <select
              class="form-select"
              id="tipo_de_unidad_de_fracciones"
              name="tipo_de_unidad_de_fracciones"
              required
            >
              <option value="UN" selected>UNIDAD</option>
              <option value="KG">KILO</option>
              <option value="GR">GRAMO</option>
              <option value="LT">LITRO</option>
              <option value="ML">MILILITRO</option>
              <option value="OZ">ONZA</option>
            </select>
          </div>

          <div class="form-group col-md-4">
            <label for="proveedor_asignado">Proveedor Asignado</label>
            <input
              type="text"
              class="form-control"
              id="proveedor_asignado"
              name="proveedor_asignado"
            />
          </div>
        </div>

        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="stock_min_temporada_baja"
              >Stock Mínimo Temp. Baja</label
            >
            <input
              type="number"
              class="form-control"
              id="stock_min_temporada_baja"
              name="stock_min_temporada_baja"
              required
            />
          </div>
          <div class="form-group col-md-4">
            <label for="stock_min_temporada_alta"
              >Stock Mínimo Temp. ALTA</label
            >
            <input
              type="number"
              class="form-control"
              id="stock_min_temporada_alta"
              name="stock_min_temporada_alta"
              required
            />
          </div>
        </div>
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="stock_max_temporada_baja"
              >Stock Máximo Temp. Baja</label
            >
            <input
              type="number"
              class="form-control"
              id="stock_max_temporada_baja"
              name="stock_max_temporada_baja"
              required
            />
          </div>
          <div class="form-group col-md-4">
            <label for="stock_max_temporada_alta"
              >Stock Máximo Temp. ALTA</label
            >
            <input
              type="number"
              class="form-control"
              id="stock_max_temporada_alta"
              name="stock_max_temporada_alta"
              required
            />
          </div>
        </div>
        <input type="submit" class="btn
        <?php echo $editar ? "btn-warning" : "btn-primary" ?>"
        id="crear-producto" value="Guardar Ficha" />
        <a class="btn btn-warning" href="./../listado-catalogo">Salir</a>
      </form>
    </div>
  </div>
</div>

<div
  class="modal modal-sm fade"
  id="noti"
  tabindex="-1"
  aria-labelledby="noti-label"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <i class="fa-solid fa-plus fs-3 me-3" id="icono"></i>
        <h1 class="modal-title fs-5" id="noti-label">
          Gestionar grupos de catálogo
        </h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Aceptar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const apiTipoDeProductoUrl = "<?php echo URL_API_NUEVA ?>/tipos-productos";
  const apiGruposDeLaCartaUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiCentralDeCostosUrl = "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";

  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  async function wrapper() {
    mostrarAlertaSiHayMensaje();

    await cargarClasificacionVentas();
    await cargarCentralCostos();
    await cargarTiposDeProducto();

    if (id) {
      await cargarProducto();
    } else {
      await cargarCodigoProducto();
    }

    prepararFormulario();
  }

  async function cargarProducto() {
    try {
      const response = await fetch(`${apiProductosUrl}/${id}`);
      const data = await response.json();

      document.getElementById("nombre_producto").value = data.nombre_producto;
      document.getElementById("codigo").value = data.codigo;
      document.getElementById("clasificacion_ventas").value = data.id_grupo;
      document.getElementById("central_costos").value =
        data.id_central_de_costos;
      document.getElementById("tipo_producto").value = data.id_tipo_de_producto;
      document.getElementById("fecha_vigencia").value = data.fecha_de_vigencia;
      document.getElementById("stock_min_temporada_baja").value =
        data.stock_min_temporada_baja;
      document.getElementById("stock_max_temporada_baja").value =
        data.stock_max_temporada_baja;
      document.getElementById("stock_min_temporada_alta").value =
        data.stock_min_temporada_alta;
      document.getElementById("stock_max_temporada_alta").value =
        data.stock_max_temporada_alta;
      document.getElementById("tipo_de_unidad").value = data.tipo_de_unidad;
      document.getElementById("cantidad_de_fracciones").value =
        data.cantidad_de_fracciones;
      document.getElementById("tipo_de_unidad_de_fracciones").value =
        data.tipo_de_unidad_de_fracciones;

    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al cargar el producto", "consultar");
    }
  }

  async function cargarClasificacionVentas() {
    try {
      const response = await fetch(apiGruposDeLaCartaUrl);
      const data = await response.json();

      const clasificacionVentasSelect = document.getElementById(
        "clasificacion_ventas"
      );
      clasificacionVentasSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una clasificación";
      clasificacionVentasSelect.appendChild(defaultOption);

      let grupos = data;
      grupos = ordenarGrupos(grupos);

      grupos.forEach((clasificacionVentas) => {
        const option = crearOpcionClasificacionVentas(clasificacionVentas);
        clasificacionVentasSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las clasificaciones de ventas",
        "consultar"
      );
    }
  }

  function ordenarGrupos(grupos) {
    // ordenar los grupos por nro_orden
    grupos.sort((a, b) => {
      if (+a.nro_orden > +b.nro_orden) {
        return 1;
      } else if (+a.nro_orden < +b.nro_orden) {
        return -1;
      } else {
        return 0;
      }
    });

    // mapear a un array de grupos con un campo array de subgrupos
    grupos = grupos
      .filter((grupo) => grupo.codigo_grupo == grupo.codigo_subgrupo)
      .map((grupo) => {
        grupo.subgrupos = grupos.filter(
          (subgrupo) =>
            subgrupo.codigo_grupo == grupo.codigo_grupo &&
            subgrupo.codigo_subgrupo != subgrupo.codigo_grupo
        );
        return grupo;
      });

    // flat array de grupos y subgrupos
    return grupos.flatMap((grupo) => {
      return [grupo, ...grupo.subgrupos];
    });
  }

  function crearOpcionClasificacionVentas(clasificacionVentas) {
    const option = document.createElement("option");
    option.value = clasificacionVentas.id_grupo;

    if (
      clasificacionVentas.codigo_grupo == clasificacionVentas.codigo_subgrupo
    ) {
      option.textContent = clasificacionVentas.nombre_grupo;
      option.classList.add("fw-bold");
    } else {
      option.style.color = "#6c757d";
      option.innerHTML =
        "&nbsp;&nbsp;&nbsp;&nbsp;" + clasificacionVentas.nombre_grupo;
    }

    return option;
  }

  async function cargarCentralCostos() {
    try {
      const response = await fetch(apiCentralDeCostosUrl);
      const data = await response.json();

      const centralCostosSelect = document.getElementById("central_costos");

      centralCostosSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione una central de costos";
      centralCostosSelect.appendChild(defaultOption);

      data.forEach((centralCostos) => {
        const option = document.createElement("option");
        option.value = centralCostos.id_central_de_costos;
        option.textContent = centralCostos.nombre_del_costo;
        centralCostosSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
    }
  }

  async function cargarTiposDeProducto() {
    try {
      const response = await fetch(apiTipoDeProductoUrl);
      const data = await response.json();
      const tipoDeProductoSelect = document.getElementById("tipo_producto");

      tipoDeProductoSelect.innerHTML = "";

      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.text = "Seleccione un tipo de producto";
      tipoDeProductoSelect.appendChild(defaultOption);

      data.forEach((tipoDeProducto) => {
        const option = document.createElement("option");
        option.value = tipoDeProducto.id_tipo_producto;
        option.textContent = tipoDeProducto.nombre_tipo_de_producto;
        tipoDeProductoSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      mostrarAlert(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
    }
  }

  async function crearProducto(editar) {
    const producto = {
      nombre_producto: document.getElementById("nombre_producto").value,
      codigo: document.getElementById("codigo").value,
      tipo_de_unidad: document.getElementById("tipo_de_unidad").value,
      id_grupo: document.getElementById("clasificacion_ventas").value,
      id_central_de_costos: document.getElementById("central_costos").value,
      id_tipo_de_producto: document.getElementById("tipo_producto").value,
      fecha_de_vigencia: document.getElementById("fecha_vigencia").value,
      stock_min_temporada_baja: document.getElementById(
        "stock_min_temporada_baja"
      ).value,
      stock_max_temporada_baja: document.getElementById(
        "stock_max_temporada_baja"
      ).value,
      stock_min_temporada_alta: document.getElementById(
        "stock_min_temporada_alta"
      ).value,
      stock_max_temporada_alta: document.getElementById(
        "stock_max_temporada_alta"
      ).value,
      cantidad_de_fracciones: document.getElementById("cantidad_de_fracciones")
        .value,
      tipo_de_unidad_de_fracciones: document.getElementById(
        "tipo_de_unidad_de_fracciones"
      ).value,
    };

    const url = apiProductosUrl + (editar ? "/" + id : "/insumo-terminado");

    const options = {
      method: editar ? "PUT" : "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(producto),
    };

    try {
      const response = await fetch(url, options);
      const data = await response.json();
      console.log(data);

      window.location.href = `./../listado-catalogo/?ok&mensaje=Producto ${
        editar ? "actualizado" : "creado"
      } correctamente&op=${editar ? "editar" : "crear"}`;
    } catch (error) {
      console.error(error);
      mostrarAlert("error", "Error al crear el producto", "crear");
    }
  }

  async function cargarCodigoProducto() {
    try {
      const url = apiConfigUrl + "/6/codigo"; // 6 es el id de los productos
      const response = await fetch(url);
      const data = await response.json();

      const codigo = document.getElementById("codigo");
      codigo.value = data.codigo;
    } catch (error) {
      mostrarAlert(
        "error",
        "Error al cargar el código del producto",
        "consultar"
      );
    }
  }

  function prepararFormulario() {
    const form = document.getElementById("form-crear-producto");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      crearProducto(id);
    });
  }

  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
