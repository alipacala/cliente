<?php
require "../../inc/header.php";

session_start();
$tiempoTranscurrido = isset($_SESSION['ultima_actividad']) ? time() - $_SESSION['ultima_actividad'] : null;
if ($tiempoTranscurrido && ($tiempoTranscurrido > TIEMPO_INACTIVIDAD)) {
  session_unset();
  session_destroy();
}
$logueado = isset($_SESSION["logueado"]) ? $_SESSION["logueado"] : false;
mostrarHeader("pagina-funcion", $logueado);

$editar = isset($_GET["id"]) ? $_GET["id"] : false;
?>

<div class="container my-5 main-cont">
  <div class="card">
    <div class="card-header py-3">
      <h2 class="text-center"><?php echo $editar ? "Editar" : "Crear" ?> Ficha de Hospedaje</h2>
    </div>
    <div class="card-body">
      <form id="form-crear-producto">
        <div class="row mb-3">
          <div class="form-group col-md-3">
            <label for="nombre_producto">Nombre del Producto</label>
            <input
              type="text"
              class="form-control"
              id="nombre_producto"
              name="nombre_producto"
              required
            />
          </div>
          <div class="form-group col-md-6">
            <label for="descripcion_del_producto">Descripción</label>
            <input
              type="text"
              class="form-control"
              id="descripcion_del_producto"
              name="descripcion_del_producto"
              required
            />
          </div>
          <div class="form-group col-md-3">
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
          <div class="form-group col-md-4">
            <label for="fecha_vigencia">Fecha de Vigencia</label>
            <input
              type="date"
              class="form-control"
              id="fecha_vigencia"
              name="fecha_vigencia"
              value=<?php echo date("Y-m-d"); ?>
              required
            />
          </div>
        </div>

        <input
          type="submit"
          class="btn <?php echo $editar ? "btn-warning" : "btn-primary" ?>"
          id="crear-producto"
          value="Guardar Ficha"
        />
        <a class="btn btn-warning" href="./../listado-catalogo">Salir</a>
      </form>
    </div>
  </div>
</div>

<script>
  const apiGruposDeLaCartaUrl = "<?php echo URL_API_NUEVA ?>/grupos-carta";
  const apiCentralDeCostosUrl = "<?php echo URL_API_NUEVA ?>/centrales-costos";
  const apiProductosUrl = "<?php echo URL_API_NUEVA ?>/productos";
  const apiConfigUrl = "<?php echo URL_API_NUEVA ?>/config";
  
  const params = new URLSearchParams(window.location.search);
  const id = params.get("id");

  function wrapper() {
    cargarClasificacionVentas();
    cargarCentralCostos();
    
    if (id) {
      cargarProducto();
    } else {
      cargarCodigoProductoHospedaje();
    }

    prepararFormulario();
  }
  
  async function cargarCodigoProductoHospedaje() {
    try {
      const url = apiConfigUrl + "/8/codigo"; // 8 es el id de los productos de hospedaje
      const response = await fetch(url);
      const data = await response.json();

      const codigo = document.getElementById("codigo");
      codigo.value = data.codigo;
    } catch (error) {
      console.error("Error al cargar el código del producto: ", error);
    }
  }

  async function cargarProducto() {
    try {
      const response = await fetch(`${apiProductosUrl}/${id}`);
      const data = await response.json();

      document.getElementById("nombre_producto").value =
        data.nombre_producto;
      document.getElementById("descripcion_del_producto").value =
        data.descripcion_del_producto;
      document.getElementById("codigo").value = data.codigo;
      document.getElementById("clasificacion_ventas").value = data.id_grupo;
      document.getElementById("central_costos").value =
        data.id_central_de_costos;
      document.getElementById("fecha_vigencia").value =
        data.fecha_de_vigencia;
    } catch (error) {
      console.error("Error al cargar el producto: ", error);
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
      console.error("Error al cargar los grupos de la carta: ", error);
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
      console.error("Error al cargar las centrales de costos: ", error);
    }
  }

  async function crearProducto(editar) {
    const producto = {
      nombre_producto: document.getElementById("nombre_producto").value,
      descripcion_del_producto: document.getElementById("descripcion_del_producto")
        .value,
      codigo: document.getElementById("codigo").value,
      id_grupo: document.getElementById("clasificacion_ventas").value,
      id_central_de_costos: document.getElementById("central_costos").value,
      fecha_de_vigencia: document.getElementById("fecha_vigencia").value,
      activo: 1,
    };

    const url = apiProductosUrl + (editar ? "/" + id : "/hospedaje");

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

      window.location.href = `./../listado-catalogo/?ok&mensaje=Hospedaje ${editar ? "actualizado" : "creado"} correctamente&op=${
        editar ? "editar" : "crear"
      }`;
    } catch (error) {
      console.error("Error al crear el producto: ", error);
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
