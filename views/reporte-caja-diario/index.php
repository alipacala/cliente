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
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center">Reporte diario de caja</h2>
    </div>
    <div class="card-body">
      <form id="form-reporte">
        <div class="row mb-3">
          <div class="form-group col-md-4">
            <label for="fecha">Fecha</label>
            <input type="text" class="form-control" id="fecha" name="fecha"
            value="<?php echo date("Y-m-d") ?>" required />
          </div>
        </div>
        <input type="submit" class="btn btn-primary"
        id="generar-reporte" value="Ok" />
        <a class="btn btn-warning" href="./../">Salir</a>
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
        <h1 class="modal-title fs-5" id="noti-label">
          Cerrar el turno
        </h1>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <div class="modal-body">
        ¿Quiere cerrar el turno?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          Aceptar
        </button>
        <button type="button" class="btn btn-outline-secondary">
          Cancelar
      </div>
    </div>
  </div>
</div>

<script>
  const apiReportesUrl = "<?php echo URL_API_NUEVA ?>/caja-diario";

  function wrapper() {
    prepararFormulario();
  }

  async function generarReporte() {
    try {
      const response = await fetch(`${apiProductosUrl}/${id}`);


    } catch (error) {
      mostrarNotificacion("error", "Error al cargar el producto", "consultar");
      console.error("Error al cargar el producto: ", error);
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
      mostrarNotificacion(
        "error",
        "Error al cargar el código del producto",
        "consultar"
      );
      console.error("Error al cargar el código del producto: ", error);
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
      mostrarNotificacion(
        "error",
        "Error al cargar las clasificaciones de ventas",
        "consultar"
      );
      console.error("Error al cargar las clasificaciones de ventas: ", error);
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
      mostrarNotificacion(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
      console.error("Error al cargar las centrales de costos: ", error);
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
      mostrarNotificacion(
        "error",
        "Error al cargar las centrales de costos",
        "consultar"
      );
      console.error("Error al cargar las centrales de costos: ", error);
    }
  }

  async function crearProducto(editar) {
    const producto = {
      nombre_producto: document.getElementById("nombre_producto").value,
      codigo: document.getElementById("codigo").value,
      tipo_de_unidad: document.getElementById("tipo_unidad").value,
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
      stock_min_temporada_altas: document.getElementById(
        "stock_min_temporada_alta"
      ).value,
      stock_max_temporada_alta: document.getElementById(
        "stock_max_temporada_alta"
      ).value,
      cantidad_de_fracciones:
        document.getElementById("cantidad_unidades").value,
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
    } catch (error) {
      console.error("Error al crear el producto: ", error);
    }
  }

  function prepararFormulario() {
    const form = document.getElementById("form-reporte");
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      generarReporte();
    });
  }
  
  window.addEventListener("load", wrapper);
</script>

<?php
require "../../inc/footer.php";
?>
